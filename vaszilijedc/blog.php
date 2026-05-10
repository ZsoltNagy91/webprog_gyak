<?php
require_once 'config/database.php';

// Bejegyzések lekérése az adatbázisból
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html lang="hu">

<head>

    <title>Blog - Vaszilij EDC</title>

    <meta charset="utf-8" />

    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet"
          href="assets/css/main.css" />

    <noscript>
        <link rel="stylesheet"
              href="assets/css/noscript.css" />
    </noscript>

</head>

<body class="is-preload">

<div id="wrapper">

    <!-- HEADER -->
    <header id="header">
        <a href="index.html" class="logo">
            Vaszilij EDC
        </a>
    </header>

    <!-- NAV -->
    <nav id="nav">

        <ul class="links">

            <li>
                <a href="index.html">Kezdőlap</a>
            </li>

            <li class="active">
                <a href="blog.php">Blog</a>
            </li>

            <li>
                <a href="articles.html">Írások</a>
            </li>

            <li>
                <a href="contact.php">Üzenetküldés</a>
            </li>

            <li>
                <a href="support.html">Támogatás</a>
            </li>

            <li>
                <a href="login.html">Belépés</a>
            </li>

        </ul>

        <ul class="icons">

            <li>
                <a href="#" class="icon brands fa-facebook-f">
                    <span class="label">Facebook</span>
                </a>
            </li>

            <li>
                <a href="#" class="icon brands fa-instagram">
                    <span class="label">Instagram</span>
                </a>
            </li>

            <li>
                <a href="#" class="icon brands fa-youtube">
                    <span class="label">Youtube</span>
                </a>
            </li>

        </ul>

    </nav>

    <!-- MAIN -->
    <div id="main">

        <!-- HERO -->
        <section class="post featured">

            <header class="major">

                <span class="date">
                    Vaszilij EDC Blog
                </span>

                <h1>
                    „Ezeket<br />
                    a dolgokat<br />
                    cipelem.”
                </h1>

                <p>
                    Kések, EDC felszerelések,
                    outdoor világ, bemutatók és gondolatok.
                </p>

            </header>

        </section>

        <!-- BLOG BEJEGYZÉSEK -->
        <section class="posts">

            <?php foreach ($posts as $post): ?>

                <article>

                    <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>"
                       class="image fit">

                        <img src="<?php echo htmlspecialchars($post['image']); ?>"
     alt="<?php echo htmlspecialchars($post['title']); ?>"
     style="width: 100%; height: 320px; object-fit: cover;">

                    </a>

                    <header>

                        <h2>
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h2>

                        <p>
                            <?php echo date('Y. m. d.', strtotime($post['created_at'])); ?>
                            |
                            <?php echo htmlspecialchars($post['category']); ?>
                        </p>

                    </header>

                    <p>
                        <?php echo htmlspecialchars($post['summary']); ?>
                    </p>

                    <ul class="actions special">

                        <li>
                            <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>"
                               class="button">
                                Bővebben
                            </a>
                        </li>

                    </ul>

                </article>

            <?php endforeach; ?>

        </section>

    </div>

    <!-- FOOTER -->
    <footer id="footer">

        <section>

            <h3>Email</h3>

            <p>
                kapcsolat@vaszilijedc.hu
            </p>

        </section>

    </footer>

</div>

<!-- SCRIPTS -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.scrollex.min.js"></script>
<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/browser.min.js"></script>
<script src="assets/js/breakpoints.min.js"></script>
<script src="assets/js/util.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>