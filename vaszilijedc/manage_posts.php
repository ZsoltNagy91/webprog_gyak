<?php
session_start();
require_once 'config/database.php';

// Admin szerepkör ellenőrzése
$role = $_SESSION['role'] ?? 'guest';
if ($role !== 'admin') {
    header('Location: index.php'); // Ha nem admin, irányítsd a főoldalra
    exit;
}

// Hibaüzenet
$error = '';

// Új bejegyzés létrehozása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_post'])) {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if ($title === '' || $slug === '' || $category === '' || $summary === '' || $content === '') {
        $error = 'Minden mezőt ki kell tölteni!';
    } else {
        // Új bejegyzés mentése
        $stmt = $pdo->prepare("INSERT INTO posts (title, slug, category, summary, content, image, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $slug, $category, $summary, $content, $image]);
        header('Location: manage_posts.php'); // A mentés után frissítjük az oldalt
        exit;
    }
}

// Bejegyzések lekérése
$sql = "SELECT id, title, category, summary, image, created_at, slug FROM posts ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html lang="hu">
<head>
    <meta charset="utf-8" />
    <title>Bejegyzések kezelése - Vaszilij EDC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>

<div id="wrapper">

    <!-- HEADER -->
    <header id="header">
        <a href="index.php" class="logo">VASZILIJ EDC</a>
    </header>

    <!-- NAV -->
    <nav id="nav">
        <ul class="links">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li class="active"><a href="manage_posts.php">Bejegyzések kezelése</a></li>
            <?php if ($_SESSION['role'] !== 'guest'): ?>
                <li><a href="logout.php">Kilépés</a></li>
            <?php else: ?>
                <li><a href="login.php">Belépés</a></li>
            <?php endif; ?>

        </ul>
         <ul class="icons">
            <li>
                <a href="https://www.facebook.com/vaszilijedc"
                   target="_blank"
                   class="icon brands fa-facebook-f">
                    <span class="label">Facebook</span>
                </a>
            </li>

            <li>
                <a href="https://www.instagram.com/vaszilijedc"
                   target="_blank"
                   class="icon brands fa-instagram">
                    <span class="label">Instagram</span>
                </a>
            </li>

            <li>
                <a href="https://www.youtube.com/@vaszilijedc"
                   target="_blank"
                   class="icon brands fa-youtube">
                    <span class="label">YouTube</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- MAIN -->
    <div id="main">

        <!-- Új bejegyzés -->
        <section>
            <h2>Új Bejegyzés Létrehozása</h2>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <div>
                    <label for="title">Cím</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div>
                    <label for="slug">Slug (URL-barát név)</label>
                    <input type="text" name="slug" id="slug" required>
                </div>
                <div>
                    <label for="category">Kategória</label>
                    <input type="text" name="category" id="category" required>
                </div>
                <div>
                    <label for="summary">Rövid összefoglaló</label>
                    <textarea name="summary" id="summary" required></textarea>
                </div>
                <div>
                    <label for="content">Tartalom</label>
                    <textarea name="content" id="content" required></textarea>
                </div>
                <div>
                    <label for="image">Kép URL</label>
                    <input type="text" name="image" id="image">
                </div>
                <div>
                    <button type="submit" name="new_post">Létrehozás</button>
                </div>
            </form>
        </section>

        <!-- Bejegyzések listázása -->
        <section>
            <h2>Meglévő Bejegyzések</h2>
            <table>
                <thead>
                    <tr>
                        <th>Kép</th>
                        <th>Bejegyzés</th>
                        <th>Kategória</th>
                        <th>Dátum</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($post['image']); ?>" alt="" width="120" /></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['category']); ?></td>
                            <td><?php echo date('Y.m.d.', strtotime($post['created_at'])); ?></td>
                            <td><a href="edit_post.php?id=<?php echo $post['id']; ?>">Szerkesztés</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </div>

</div>

<footer id="footer">
    <section>
        <h3>Vaszilij EDC</h3>
        <p>EDC • Kések • Outdoor • Felszerelések</p>
       
    </section>
</footer>

<script src="assets/js/main.js"></script>

</body>
</html>