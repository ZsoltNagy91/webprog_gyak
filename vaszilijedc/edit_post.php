<?php
session_start();
require_once 'config/database.php';

/*

| TESZT MÓD


*/
$_SESSION['role'] = 'admin';
$_SESSION['role'] = 'user';

$role = $_SESSION['role'] ?? 'guest';

/* Csak admin és user férhet hozzá */
if (!in_array($role, ['admin', 'user'])) {
    die('Nincs jogosultságod az oldal megtekintéséhez.');
}

/* ID ellenőrzése */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Érvénytelen bejegyzés azonosító.');
}

/* Bejegyzés lekérése */
$stmt = $pdo->prepare("
    SELECT id, title, slug, category, summary, image, content, created_at
    FROM posts
    WHERE id = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('A bejegyzés nem található.');
}
?>
<!DOCTYPE HTML>
<html lang="hu">

<head>
    <meta charset="utf-8" />
    <title>Blogbejegyzés szerkesztése - Vaszilij EDC</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" href="assets/css/main.css" />

    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>

    <style>
        .editor-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .editor-title {
            width: 100%;
            max-width: 1100px;
            text-align: center;
            margin-bottom: 4rem;
        }

        .editor-title h1 {
            margin-bottom: 1rem;
        }

        .editor-title p {
            color: #666;
        }

        .editor-form {
            background: #ffffff;
            padding: 3rem;
            border-radius: 6px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 1100px;
        }

        .editor-form .field {
            margin-bottom: 2rem;
        }

        .editor-form label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .editor-form input[type="text"],
        .editor-form input[type="file"],
        .editor-form textarea {
            width: 100%;
        }

        .current-image {
            margin-top: 1rem;
            max-width: 300px;
            border-radius: 6px;
        }

        .actions.special {
            margin-top: 3rem;
        }

        @media screen and (max-width: 980px) {
            .editor-form {
                padding: 2rem;
            }
        }
    </style>
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
            <li><a href="articles.html">Írások</a></li>
            <li><a href="contact.php">Üzenetküldés</a></li>
            <li><a href="support.html">Támogatás</a></li>
            <li class="active"><a href="manage_posts.php">Bejegyzések kezelése</a></li>
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

        <section class="editor-wrapper">

            <div class="editor-title">
                <h1>Blogbejegyzés szerkesztése</h1>
                <p>
                    Itt módosíthatod a kiválasztott bejegyzés adatait.
                </p>
            </div>

            <div class="editor-form">

                <form method="post" enctype="multipart/form-data">

                    <!-- CÍM -->
                    <div class="field">
                        <label for="title">Cím</label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="<?php echo htmlspecialchars($post['title']); ?>"
                               required>
                    </div>

                    <!-- SLUG -->
                    <div class="field">
                        <label for="slug">Slug (URL-barát név)</label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="<?php echo htmlspecialchars($post['slug']); ?>"
                               required>
                    </div>

                    <!-- KATEGÓRIA -->
                    <div class="field">
                        <label for="category">Kategória</label>
                        <input type="text"
                               name="category"
                               id="category"
                               value="<?php echo htmlspecialchars($post['category']); ?>">
                    </div>

                    <!-- ÖSSZEFOGLALÓ -->
                    <div class="field">
                        <label for="summary">Rövid összefoglaló</label>
                        <textarea name="summary"
                                  id="summary"
                                  rows="5"><?php echo htmlspecialchars($post['summary']); ?></textarea>
                    </div>

                    <!-- AKTUÁLIS KÉP -->
                    <div class="field">
                        <label>Jelenlegi kép</label>

                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>"
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="current-image">
                        <?php else: ?>
                            <p>Nincs feltöltött kép.</p>
                        <?php endif; ?>
                    </div>

                    <!-- ÚJ KÉP -->
                    <div class="field">
                        <label for="image">Új kép feltöltése</label>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*">
                    </div>

                    <!-- TELJES TARTALOM -->
                    <div class="field">
                        <label for="content">Teljes tartalom</label>
                        <textarea name="content"
                                  id="content"
                                  rows="20"><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>

                    <!-- MENTÉS -->
                    <ul class="actions special">
                        <li>
                            <button type="submit"
                                    class="button primary large">
                                Módosítások mentése
                            </button>
                        </li>
                    </ul>

                </form>

            </div>

        </section>

    </div>

    <!-- FOOTER -->
    <footer id="footer">
        <section>
            <h3>Vaszilij EDC</h3>
            <p>EDC • Kések • Outdoor • Felszerelések</p>
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

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        height: 500
    });
</script>

</body>
</html>