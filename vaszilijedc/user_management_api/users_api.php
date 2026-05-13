<?php
// ============================================================
//  users_api.php  –  User CRUD API
//  Végpontok:
//    POST   /users_api.php?action=register   – Regisztráció
//    POST   /users_api.php?action=login      – Bejelentkezés
//    POST   /users_api.php?action=logout     – Kijelentkezés
//    GET    /users_api.php?action=me         – Saját adatok
//    GET    /users_api.php?action=list       – Lista (admin: mindenki, user: csak maga)
//    PUT    /users_api.php?action=update     – Módosítás  (admin: bárkit, user: csak magát)
//    DELETE /users_api.php?action=delete&id= – Törlés     (csak admin)
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS preflight kérés kezelése
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/database.php'; // $pdo

// ── Segédfüggvények ──────────────────────────────────────────

function respond(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getBody(): array
{
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

function requireAuth(): array
{
    if (empty($_SESSION['user_id'])) {
        respond(401, ['error' => 'Bejelentkezés szükséges.']);
    }
    return [
        'id'   => $_SESSION['user_id'],
        'role' => $_SESSION['role'],
    ];
}

function requireAdmin(): void
{
    $user = requireAuth();
    if ($user['role'] !== 'admin') {
        respond(403, ['error' => 'Admin jogosultság szükséges.']);
    }
}

function validateUsername(string $username): ?string
{
    $username = trim($username);
    if (strlen($username) < 3)  return 'A felhasználónév legalább 3 karakter legyen.';
    if (strlen($username) > 50) return 'A felhasználónév maximum 50 karakter lehet.';
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return 'A felhasználónév csak betűt, számot és _ karaktert tartalmazhat.';
    }
    return null;
}

function validatePassword(string $password): ?string
{
    if (strlen($password) < 6) return 'A jelszó legalább 6 karakter legyen.';
    return null;
}

// ── Router ───────────────────────────────────────────────────

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

match (true) {
    $action === 'register' && $method === 'POST'   => handleRegister($pdo),
    $action === 'login'    && $method === 'POST'   => handleLogin($pdo),
    $action === 'logout'   && $method === 'POST'   => handleLogout(),
    $action === 'me'       && $method === 'GET'    => handleMe($pdo),
    $action === 'list'     && $method === 'GET'    => handleList($pdo),
    $action === 'update'   && $method === 'PUT'    => handleUpdate($pdo),
    $action === 'delete'   && $method === 'DELETE' => handleDelete($pdo),
    default => respond(404, ['error' => 'Ismeretlen végpont.'])
};

// ── Végpont-kezelők ──────────────────────────────────────────

// POST /users_api.php?action=register
// Body: { "username": "...", "password": "...", "role": "user" }
function handleRegister(PDO $pdo): void
{
    $body = getBody();

    $username = trim($body['username'] ?? '');
    $password = $body['password'] ?? '';
    $role     = $body['role'] ?? 'user';

    // Validáció
    if ($err = validateUsername($username)) respond(422, ['error' => $err]);
    if ($err = validatePassword($password)) respond(422, ['error' => $err]);
    if (!in_array($role, ['admin', 'user'], true)) {
        respond(422, ['error' => 'Érvénytelen szerepkör.']);
    }

    // Egyedi username ellenőrzés
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        respond(409, ['error' => 'Ez a felhasználónév már foglalt.']);
    }

    // Mentés
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, password_hash, role, is_active) VALUES (?, ?, ?, 1)'
    );
    $stmt->execute([$username, $hash, $role]);
    $newId = (int) $pdo->lastInsertId();

    respond(201, [
        'message' => 'Sikeres regisztráció.',
        'user'    => ['id' => $newId, 'username' => $username, 'role' => $role],
    ]);
}

// POST /users_api.php?action=login
// Body: { "username": "...", "password": "..." }
function handleLogin(PDO $pdo): void
{
    $body = getBody();

    $username = trim($body['username'] ?? '');
    $password = $body['password'] ?? '';

    if (!$username || !$password) {
        respond(422, ['error' => 'Felhasználónév és jelszó megadása kötelező.']);
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        respond(401, ['error' => 'Hibás felhasználónév vagy jelszó.']);
    }

    if (!(bool) $user['is_active']) {
        respond(403, ['error' => 'A fiók inaktív. Kérj segítséget az adminisztrátortól.']);
    }

    // Session indítása
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    respond(200, [
        'message' => 'Sikeres bejelentkezés.',
        'user'    => [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
        ],
    ]);
}

// POST /users_api.php?action=logout
function handleLogout(): void
{
    $_SESSION = [];
    session_destroy();
    respond(200, ['message' => 'Kijelentkezés sikeres.']);
}

// GET /users_api.php?action=me
function handleMe(PDO $pdo): void
{
    $session = requireAuth();

    $stmt = $pdo->prepare(
        'SELECT id, username, role, is_active, created_at FROM users WHERE id = ?'
    );
    $stmt->execute([$session['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) respond(404, ['error' => 'Felhasználó nem található.']);

    respond(200, ['user' => $user]);
}

// GET /users_api.php?action=list
// Admin: az összes user | User: csak saját maga
function handleList(PDO $pdo): void
{
    $session = requireAuth();

    if ($session['role'] === 'admin') {
        $stmt = $pdo->query(
            'SELECT id, username, role, is_active, created_at FROM users ORDER BY id'
        );
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, username, role, is_active, created_at FROM users WHERE id = ?'
        );
        $stmt->execute([$session['id']]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    respond(200, ['users' => $users]);
}

// PUT /users_api.php?action=update
// Body: { "id": 1, "username": "...", "password": "...", "role": "...", "is_active": 1 }
// Admin: bármely mezőt bárkin | User: csak saját username + password
function handleUpdate(PDO $pdo): void
{
    $session = requireAuth();
    $body    = getBody();

    $targetId = (int) ($body['id'] ?? 0);
    if (!$targetId) respond(422, ['error' => 'Hiányzó user id.']);

    // Jogosultság: user csak magát szerkesztheti
    if ($session['role'] !== 'admin' && $session['id'] !== $targetId) {
        respond(403, ['error' => 'Nincs jogosultságod más felhasználó módosításához.']);
    }

    // Céluser lekérése
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$targetId]);
    $target = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$target) respond(404, ['error' => 'Felhasználó nem található.']);

    // Mezők összeállítása
    $fields = [];
    $params = [];

    if (isset($body['username'])) {
        $username = trim($body['username']);
        if ($err = validateUsername($username)) respond(422, ['error' => $err]);

        // Egyedi ellenőrzés (saját ID kivételével)
        $chk = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
        $chk->execute([$username, $targetId]);
        if ($chk->fetch()) respond(409, ['error' => 'Ez a felhasználónév már foglalt.']);

        $fields[] = 'username = ?';
        $params[] = $username;
    }

    if (isset($body['password'])) {
        $password = $body['password'];
        if ($err = validatePassword($password)) respond(422, ['error' => $err]);
        $fields[] = 'password_hash = ?';
        $params[] = password_hash($password, PASSWORD_BCRYPT);
    }

    // Role és is_active csak adminnak
    if ($session['role'] === 'admin') {
        if (isset($body['role'])) {
            if (!in_array($body['role'], ['admin', 'user'], true)) {
                respond(422, ['error' => 'Érvénytelen szerepkör.']);
            }
            $fields[] = 'role = ?';
            $params[] = $body['role'];
        }
        if (isset($body['is_active'])) {
            $fields[] = 'is_active = ?';
            $params[] = (int)(bool) $body['is_active'];
        }
    }

    if (empty($fields)) respond(422, ['error' => 'Nincs módosítandó adat.']);

    $params[] = $targetId;
    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
    $pdo->prepare($sql)->execute($params);

    respond(200, ['message' => 'Felhasználó sikeresen módosítva.']);
}

// DELETE /users_api.php?action=delete&id=
// Csak admin, saját magát nem törölheti
function handleDelete(PDO $pdo): void
{
    requireAdmin();
    $session = requireAuth();

    $targetId = (int) ($_GET['id'] ?? 0);
    if (!$targetId) respond(422, ['error' => 'Hiányzó user id.']);

    if ($targetId === $session['id']) {
        respond(403, ['error' => 'Saját fiókot nem törölhetsz.']);
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
    $stmt->execute([$targetId]);
    if (!$stmt->fetch()) respond(404, ['error' => 'Felhasználó nem található.']);

    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$targetId]);

    respond(200, ['message' => 'Felhasználó törölve.']);
}
