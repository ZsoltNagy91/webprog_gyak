<?php require_once '../config/init.php'; ?>
<?php
// user_manager_form.php
// Admin: minden felhasználót lát és szerkeszthet / törölhet
// User: csak saját magát látja és szerkesztheti

// Bejelentkezés ellenőrzés – ha nincs session, visszairányítás
if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$currentUserId   = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];
$currentRole     = $_SESSION['role'];
$isAdmin         = $currentRole === 'admin';
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználókezelés</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Epilogue:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #080a0f;
            --surface: #0e1117;
            --surface2: #151820;
            --border: #1f2433;
            --border2: #2a3048;
            --accent: #3b82f6;
            --accent-g: #6366f1;
            --text: #e2e8f8;
            --muted: #5a6480;
            --muted2: #8892aa;
            --danger: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
            --admin-clr: #a855f7;
            --user-clr: #3b82f6;
            --r: 10px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Epilogue', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Háttér textúra ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 80% 10%, rgba(99, 102, 241, .07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 10% 80%, rgba(59, 130, 246, .05) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Navbar ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(8, 10, 15, .85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: .875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -.02em;
            background: linear-gradient(135deg, var(--accent), var(--accent-g));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .875rem;
            color: var(--muted2);
        }

        .nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-g));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: .8rem;
            color: #fff;
            flex-shrink: 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: .2rem .55rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .badge-admin {
            background: rgba(168, 85, 247, .15);
            color: var(--admin-clr);
            border: 1px solid rgba(168, 85, 247, .25);
        }

        .badge-user {
            background: rgba(59, 130, 246, .12);
            color: var(--user-clr);
            border: 1px solid rgba(59, 130, 246, .2);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--border2);
            color: var(--muted2);
            font-family: 'Epilogue', sans-serif;
            font-size: .825rem;
            font-weight: 500;
            padding: .45rem .9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }

        .btn-logout:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* ── Fő tartalom ── */
        .main {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--text);
        }

        .page-subtitle {
            color: var(--muted);
            font-size: .9rem;
            margin-top: .3rem;
        }

        /* ── Stat kártyák (csak adminnak) ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 1.25rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--line-clr, var(--accent));
        }

        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        /* ── Táblázat container ── */
        .table-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            gap: 1rem;
            flex-wrap: wrap;
        }

        .toolbar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .45rem .85rem;
            min-width: 220px;
        }

        .search-box svg {
            color: var(--muted);
            flex-shrink: 0;
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: 'Epilogue', sans-serif;
            font-size: .875rem;
            width: 100%;
        }

        .search-box input::placeholder {
            color: var(--muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: .75rem 1.25rem;
            text-align: left;
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--muted);
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, .02);
        }

        td {
            padding: 1rem 1.25rem;
            font-size: .9rem;
            vertical-align: middle;
        }

        .td-user {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent-g));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: .85rem;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 600;
            color: var(--text);
        }

        .user-id {
            font-size: .75rem;
            color: var(--muted);
        }

        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .85rem;
        }

        .status-dot::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--dot-clr, var(--muted));
            flex-shrink: 0;
        }

        .status-active {
            --dot-clr: var(--success);
            color: var(--success);
        }

        .status-inactive {
            --dot-clr: var(--muted);
            color: var(--muted);
        }

        .td-date {
            color: var(--muted2);
            font-size: .825rem;
        }

        /* ── Akció gombok ── */
        .actions {
            display: flex;
            gap: .5rem;
        }

        .btn-icon {
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: 7px;
            color: var(--muted2);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s, border-color .15s, color .15s;
            flex-shrink: 0;
        }

        .btn-icon:hover {
            background: rgba(59, 130, 246, .1);
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-icon.danger:hover {
            background: rgba(239, 68, 68, .1);
            border-color: var(--danger);
            color: var(--danger);
        }

        .btn-icon:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        /* ── Üres állapot ── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--muted);
        }

        .empty-state svg {
            margin-bottom: 1rem;
            opacity: .4;
        }

        /* ── Loading ── */
        .loading-row td {
            text-align: center;
            padding: 3rem;
            color: var(--muted);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border2);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin-bottom: .75rem;
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(0, 0, 0, .7);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 440px;
            transform: translateY(16px);
            transition: transform .25s;
        }

        .modal-overlay.open .modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .modal-close {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--muted);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            transition: color .15s, border-color .15s;
        }

        .modal-close:hover {
            color: var(--text);
            border-color: var(--border2);
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: .9rem;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }

        .form-label {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--muted);
        }

        .form-input,
        .form-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Epilogue', sans-serif;
            font-size: .9rem;
            padding: .65rem .9rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
        }

        .form-input::placeholder {
            color: var(--muted);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%235a6480' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
            padding-right: 2.2rem;
            cursor: pointer;
        }

        .form-hint {
            font-size: .78rem;
            color: var(--muted);
        }

        .modal-actions {
            display: flex;
            gap: .75rem;
            margin-top: .5rem;
        }

        .btn {
            flex: 1;
            border: none;
            border-radius: 8px;
            font-family: 'Epilogue', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            padding: .7rem;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
        }

        .btn:active {
            transform: scale(.98);
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: .88;
        }

        .btn-ghost {
            background: var(--surface2);
            color: var(--muted2);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            color: var(--text);
            border-color: var(--border2);
        }

        /* ── Confirm modal ── */
        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(239, 68, 68, .12);
            border: 1px solid rgba(239, 68, 68, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--danger);
        }

        .confirm-text {
            color: var(--muted2);
            font-size: .9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            opacity: .88;
        }

        /* ── Toast értesítés ── */
        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 300;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .toast {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .875rem;
            font-weight: 500;
            min-width: 240px;
            animation: toast-in .3s ease;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .toast.success {
            border-color: rgba(34, 197, 94, .3);
        }

        .toast.error {
            border-color: rgba(239, 68, 68, .3);
        }

        .toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .toast.success .toast-dot {
            background: var(--success);
        }

        .toast.error .toast-dot {
            background: var(--danger);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateX(16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toast-out {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(16px);
            }
        }

        /* ── Reszponzív ── */
        @media (max-width: 680px) {
            .navbar {
                padding: .75rem 1rem;
            }

            .main {
                padding: 1.5rem 1rem;
            }

            .stats {
                grid-template-columns: 1fr 1fr;
            }

            .stats .stat-card:last-child {
                grid-column: span 2;
            }

            th:nth-child(4),
            td:nth-child(4),
            th:nth-child(5),
            td:nth-child(5) {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- ── Navbar ── -->
    <nav class="navbar">
        <span class="nav-brand">⬡ UserPanel</span>
        <div class="nav-right">
            <div class="nav-user">
                <div class="nav-avatar" id="nav-avatar"><?= strtoupper(substr(htmlspecialchars($currentUsername), 0, 1)) ?></div>
                <span><?= htmlspecialchars($currentUsername) ?></span>
                <span class="badge badge-<?= $currentRole ?>"><?= $isAdmin ? 'Admin' : 'User' ?></span>
            </div>
            <button class="btn-logout" onclick="logout()">Kijelentkezés</button>
        </div>
    </nav>

    <!-- ── Fő tartalom ── -->
    <main class="main">

        <div class="page-header">
            <h1 class="page-title"><?= $isAdmin ? 'Felhasználókezelés' : 'Saját profilom' ?></h1>
            <p class="page-subtitle">
                <?= $isAdmin
                    ? 'Az összes regisztrált felhasználó kezelése'
                    : 'Megtekintheted és módosíthatod a saját adataidat' ?>
            </p>
        </div>

        <?php if ($isAdmin): ?>
            <!-- Stat kártyák (csak admin) -->
            <div class="stats" id="stats-row">
                <div class="stat-card" style="--line-clr: var(--accent)">
                    <div class="stat-label">Összes felhasználó</div>
                    <div class="stat-value" id="stat-total">—</div>
                </div>
                <div class="stat-card" style="--line-clr: var(--success)">
                    <div class="stat-label">Aktív</div>
                    <div class="stat-value" id="stat-active">—</div>
                </div>
                <div class="stat-card" style="--line-clr: var(--admin-clr)">
                    <div class="stat-label">Adminok</div>
                    <div class="stat-value" id="stat-admin">—</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Táblázat -->
        <div class="table-wrap">
            <div class="table-toolbar">
                <span class="toolbar-title">
                    <?= $isAdmin ? 'Felhasználók listája' : 'Fiók adatok' ?>
                </span>
                <?php if ($isAdmin): ?>
                    <div class="search-box">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="search-input" placeholder="Keresés..." oninput="filterTable()">
                    </div>
                <?php endif; ?>
            </div>

            <div style="overflow-x:auto">
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>Felhasználó</th>
                            <th>Szerepkör</th>
                            <th>Státusz</th>
                            <?php if ($isAdmin): ?>
                                <th>Regisztrálva</th>
                            <?php endif; ?>
                            <th style="text-align:right">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <tr class="loading-row">
                            <td colspan="<?= $isAdmin ? '5' : '4' ?>">
                                <div class="spinner"></div><br>Betöltés...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ── Szerkesztő modal ── -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Felhasználó szerkesztése</span>
                <button class="modal-close" onclick="closeModal('edit-modal')">✕</button>
            </div>
            <div class="modal-form" id="edit-form">
                <input type="hidden" id="edit-id">

                <div class="form-field">
                    <label class="form-label">Felhasználónév</label>
                    <input class="form-input" type="text" id="edit-username" placeholder="felhasznalonev">
                </div>

                <div class="form-field">
                    <label class="form-label">Új jelszó</label>
                    <input class="form-input" type="password" id="edit-password" placeholder="Hagyd üresen, ha nem változtatod">
                    <span class="form-hint">Csak akkor töltsd ki, ha változtatni szeretnél</span>
                </div>

                <?php if ($isAdmin): ?>
                    <div class="form-field">
                        <label class="form-label">Szerepkör</label>
                        <select class="form-select" id="edit-role">
                            <option value="user">Felhasználó</option>
                            <option value="admin">Adminisztrátor</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Státusz</label>
                        <select class="form-select" id="edit-active">
                            <option value="1">Aktív</option>
                            <option value="0">Inaktív</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="modal-actions">
                    <button class="btn btn-ghost" onclick="closeModal('edit-modal')">Mégse</button>
                    <button class="btn btn-primary" id="edit-save-btn" onclick="saveUser()">Mentés</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Törlés megerősítő modal ── -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width:380px">
            <div class="confirm-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                    <path d="M10 11v6M14 11v6" />
                    <path d="M9 6V4h6v2" />
                </svg>
            </div>
            <div class="modal-title" style="margin-bottom:.75rem">Törlés megerősítése</div>
            <p class="confirm-text">Biztosan törölni szeretnéd <strong id="delete-username"></strong> felhasználót? Ez a művelet nem vonható vissza.</p>
            <div class="modal-actions">
                <button class="btn btn-ghost" onclick="closeModal('delete-modal')">Mégse</button>
                <button class="btn btn-danger" id="delete-confirm-btn" onclick="confirmDelete()">Törlés</button>
            </div>
        </div>
    </div>

    <!-- ── Toast container ── -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        const API = 'users_api.php';
        const IS_ADMIN = <?= $isAdmin ? 'true' : 'false' ?>;
        const MY_ID = <?= $currentUserId ?>;

        let allUsers = [];
        let deleteTarget = null;

        // ── Betöltés ─────────────────────────────────────────────────

        async function loadUsers() {
            try {
                const res = await fetch(`${API}?action=list`);
                const data = await res.json();

                if (!res.ok) {
                    if (res.status === 401) {
                        window.location.href = 'index.php';
                        return;
                    }
                    throw new Error(data.error || 'Hiba');
                }

                allUsers = data.users;
                renderTable(allUsers);
                if (IS_ADMIN) updateStats(allUsers);
            } catch (e) {
                document.getElementById('users-tbody').innerHTML =
                    `<tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Nem sikerült betölteni az adatokat.</td></tr>`;
            }
        }

        // ── Statisztikák ──────────────────────────────────────────────

        function updateStats(users) {
            document.getElementById('stat-total').textContent = users.length;
            document.getElementById('stat-active').textContent = users.filter(u => u.is_active == 1).length;
            document.getElementById('stat-admin').textContent = users.filter(u => u.role === 'admin').length;
        }

        // ── Táblázat render ───────────────────────────────────────────

        function renderTable(users) {
            const tbody = document.getElementById('users-tbody');

            if (!users.length) {
                tbody.innerHTML = `<tr><td colspan="5">
      <div class="empty-state">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
        <p>Nincs megjeleníthető felhasználó</p>
      </div>
    </td></tr>`;
                return;
            }

            tbody.innerHTML = users.map(u => {
                const initial = u.username.charAt(0).toUpperCase();
                const isMe = u.id == MY_ID;
                const active = u.is_active == 1;
                const dateStr = u.created_at ? new Date(u.created_at).toLocaleDateString('hu-HU') : '—';

                const canDelete = IS_ADMIN && !isMe;

                return `<tr data-id="${u.id}" data-name="${u.username}">
      <td>
        <div class="td-user">
          <div class="user-avatar">${initial}</div>
          <div>
            <div class="user-name">${escHtml(u.username)}${isMe ? ' <span style="color:var(--muted);font-size:.75rem;font-weight:400">(te)</span>' : ''}</div>
            <div class="user-id">#${u.id}</div>
          </div>
        </div>
      </td>
      <td><span class="badge badge-${u.role}">${u.role === 'admin' ? 'Admin' : 'User'}</span></td>
      <td>
        <span class="status-dot ${active ? 'status-active' : 'status-inactive'}">
          ${active ? 'Aktív' : 'Inaktív'}
        </span>
      </td>
      ${IS_ADMIN ? `<td class="td-date">${dateStr}</td>` : ''}
      <td style="text-align:right">
        <div class="actions" style="justify-content:flex-end">
          <button class="btn-icon" title="Szerkesztés" onclick="openEdit(${u.id})">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </button>
          ${canDelete ? `
          <button class="btn-icon danger" title="Törlés" onclick="openDelete(${u.id}, '${escHtml(u.username)}')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
            </svg>
          </button>` : ''}
        </div>
      </td>
    </tr>`;
            }).join('');
        }

        // ── Keresés ───────────────────────────────────────────────────

        function filterTable() {
            const q = document.getElementById('search-input').value.toLowerCase();
            renderTable(allUsers.filter(u =>
                u.username.toLowerCase().includes(q) || u.role.toLowerCase().includes(q)
            ));
        }

        // ── Szerkesztő modal ──────────────────────────────────────────

        function openEdit(userId) {
            const u = allUsers.find(x => x.id == userId);
            if (!u) return;

            document.getElementById('edit-id').value = u.id;
            document.getElementById('edit-username').value = u.username;
            document.getElementById('edit-password').value = '';

            if (IS_ADMIN) {
                document.getElementById('edit-role').value = u.role;
                document.getElementById('edit-active').value = u.is_active;
            }

            openModal('edit-modal');
        }

        async function saveUser() {
            const id = parseInt(document.getElementById('edit-id').value);
            const btn = document.getElementById('edit-save-btn');

            const payload = {
                id,
                username: document.getElementById('edit-username').value.trim(),
            };

            const pw = document.getElementById('edit-password').value;
            if (pw) payload.password = pw;

            if (IS_ADMIN) {
                payload.role = document.getElementById('edit-role').value;
                payload.is_active = parseInt(document.getElementById('edit-active').value);
            }

            btn.disabled = true;
            btn.textContent = 'Mentés...';

            try {
                const res = await fetch(`${API}?action=update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (res.ok) {
                    toast(data.message || 'Módosítás mentve.', 'success');
                    closeModal('edit-modal');
                    loadUsers();
                } else {
                    toast(data.error || 'Hiba történt.', 'error');
                }
            } catch {
                toast('Szerver hiba.', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Mentés';
            }
        }

        // ── Törlés modal ──────────────────────────────────────────────

        function openDelete(userId, username) {
            deleteTarget = userId;
            document.getElementById('delete-username').textContent = username;
            openModal('delete-modal');
        }

        async function confirmDelete() {
            if (!deleteTarget) return;
            const btn = document.getElementById('delete-confirm-btn');
            btn.disabled = true;
            btn.textContent = 'Törlés...';

            try {
                const res = await fetch(`${API}?action=delete&id=${deleteTarget}`, {
                    method: 'DELETE'
                });
                const data = await res.json();

                if (res.ok) {
                    toast(data.message || 'Felhasználó törölve.', 'success');
                    closeModal('delete-modal');
                    deleteTarget = null;
                    loadUsers();
                } else {
                    toast(data.error || 'Hiba történt.', 'error');
                }
            } catch {
                toast('Szerver hiba.', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Törlés';
            }
        }

        // ── Kijelentkezés ─────────────────────────────────────────────

        async function logout() {
            await fetch(`${API}?action=logout`, {
                method: 'POST'
            });
            window.location.href = '../index.php';
        }

        // ── Modal segédek ─────────────────────────────────────────────

        function openModal(id) {
            document.getElementById(id).classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        // Kattintás a háttérre → bezárás
        document.querySelectorAll('.modal-overlay').forEach(el => {
            el.addEventListener('click', e => {
                if (e.target === el) closeModal(el.id);
            });
        });

        // ── Toast ─────────────────────────────────────────────────────

        function toast(msg, type = 'success') {
            const el = document.createElement('div');
            el.className = `toast ${type}`;
            el.innerHTML = `<span class="toast-dot"></span>${escHtml(msg)}`;
            document.getElementById('toast-container').appendChild(el);
            setTimeout(() => {
                el.style.animation = 'toast-out .3s ease forwards';
                setTimeout(() => el.remove(), 300);
            }, 3200);
        }

        // ── XSS védelem ───────────────────────────────────────────────

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ── Init ──────────────────────────────────────────────────────
        loadUsers();
    </script>
</body>

</html>