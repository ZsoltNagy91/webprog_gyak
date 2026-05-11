<?php
require_once 'config/database.php';
session_start();

// A slug paraméter ellenőrzése
if (!isset($_GET['slug'])) {
    die('Érvénytelen kérés.');
}

$slug = $_GET['slug'];

// A bejegyzés lekérése az adatbázisból
$sql = "SELECT * FROM posts WHERE slug = :slug LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('A keresett bejegyzés nem található.');
}

// Admin szerepkörű felhasználó
$role = $_SESSION['role'] ?? 'guest';
?>

<!DOCTYPE HTML>
<html lang="hu">

<head>
    <meta charset="utf-8" />
    <title><?php echo htmlspecialchars($post['title']); ?> - Vaszilij EDC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="is-preload">

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
            <li><a href="contact.php">Üzenetküldés</a></li>
            <li><a href="support.php">Támogatás</a></li>
            <?php if ($role === 'admin'): ?>
                <li><a href="admin_add_post.php">Új bejegyzés</a></li>
            <?php endif; ?>
            <?php if (in_array($role, ['admin', 'user'])): ?>
                <li><a href="manage_posts.php">Kezelés</a></li>
            <?php endif; ?>
            <?php if ($role === 'guest'): ?>
                <li><a href="login.php">Belépés</a></li>
            <?php else: ?>
                <li><a href="logout.php">Kilépés</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- MAIN -->
    <div id="main">

        <!-- BEJEGYZÉS -->
        <section>
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p><strong>Kategória:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
            <p><strong>Dátum:</strong> <?php echo date('Y.m.d.', strtotime($post['created_at'])); ?></p>

            <?php if (!empty($post['image'])): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post image" />
            <?php endif; ?>

            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

            <?php if ($role === 'admin'): ?>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="button">Szerkesztés</a>
            <?php endif; ?>
        </section>

    </div>

</div>

<!-- FOOTER -->
<footer id="footer">
    <section>
        <h3>Vaszilij EDC</h3>
        <p>EDC • Kések • Outdoor • Felszerelések</p>
    </section>
</footer>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>