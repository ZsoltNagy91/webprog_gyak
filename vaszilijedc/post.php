<?php
require_once 'config/database.php';

// slug paraméter ellenőrzése
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die('A keresett bejegyzés nem található.');
}

$slug = $_GET['slug'];

// Bejegyzés lekérése az adatbázisból
$sql = "SELECT * FROM posts WHERE slug = :slug LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['slug' => $slug]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Ha nincs ilyen bejegyzés
if (!$post) {
    die('A keresett bejegyzés nem található.');
}
?>
<!DOCTYPE HTML>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($post['title']); ?> - Vaszilij EDC</title>

    <link rel="stylesheet" href="assets/css/main.css">
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css">
    </noscript>
</head>
<body class="is-preload">

<div id="wrapper">

    <!-- Header -->
    <header id="header">
        <a href="index.html" class="logo">Vaszilij EDC</a>
    </header>

    <!-- Navigation -->
    <nav id="nav">
        <ul class="links">
            <li><a href="index.html">Kezdőlap</a></li>
            <li class="active"><a href="blog.php">Blog</a></li>
            <li><a href="articles.html">Írások</a></li>
            <li><a href="contact.php">Üzenetküldés</a></li>
            <li><a href="support.html">Támogatás</a></li>
            <li><a href="login.html">Belépés</a></li>
        </ul>
    </nav>

    <!-- Main -->
    <div id="main">

        <article class="post featured">

            <header class="major">
                <span class="date">
                    <?php echo date('Y. m. d.', strtotime($post['created_at'])); ?>
                </span>

                <h1>
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>

                <p>
                    <?php echo htmlspecialchars($post['category']); ?>
                </p>
            </header>

            <?php if (!empty($post['image'])): ?>
                <span class="image main">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>"
                         alt="<?php echo htmlspecialchars($post['title']); ?>">
                </span>
            <?php endif; ?>

            <p style="white-space: pre-line;">
                <?php echo htmlspecialchars($post['content']); ?>
            </p>

            <ul class="actions special">
                <li>
                    <a href="blog.php" class="button">
                        Vissza a bloghoz
                    </a>
                </li>
            </ul>

        </article>

    </div>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.scrollex.min.js"></script>
<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/browser.min.js"></script>
<script src="assets/js/breakpoints.min.js"></script>
<script src="assets/js/util.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>