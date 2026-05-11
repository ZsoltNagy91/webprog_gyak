<?php
session_start();
require_once 'config/database.php';

/*
|----------------------------------------------------------------------
| Ha a felhasználó már be van jelentkezve, irány a kezelőfelület
|----------------------------------------------------------------------
*/
$role = $_SESSION['role'] ?? 'guest';

if ($role !== 'guest') {
    header('Location: manage_posts.php');
    exit;
}

/*
|----------------------------------------------------------------------
| Bejelentkezés feldolgozása
|----------------------------------------------------------------------
*/
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Kérlek tölts ki minden mezőt!';
    } else {

        /*
        |------------------------------------------------------------------
        | Felhasználó keresése az adatbázisban
        | Feltételezett mezők:
        | - username
        | - password
        | - role
        |------------------------------------------------------------------
        */
       $stmt = $pdo->prepare("
    SELECT id, username, password_hash AS password, role
    FROM users
    WHERE username = ?
      AND is_active = 1
");
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        /*
        |------------------------------------------------------------------
        | Egyszerű jelszó ellenőrzés
        | (Ha password_hash() van használva, akkor password_verify() kell.)
        |------------------------------------------------------------------
        */
        if ($user && $password === $user['password']) {

            /* Session adatok */
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            /* Átirányítás */
            header('Location: manage_posts.php');
            exit;

        } else {
            $error = 'Hibás felhasználónév vagy jelszó!';
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="hu">

<head>
    <meta charset="utf-8" />
    <title>Belépés - Vaszilij EDC</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" href="assets/css/main.css" />

    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>

    <style>
        .login-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-box {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            padding: 3rem;
            border-radius: 6px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        }

        .login-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .login-title h1 {
            margin-bottom: 1rem;
        }

        .field {
            margin-bottom: 2rem;
        }

        .error-message {
            background: #ffeaea;
            color: #c0392b;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            text-align: center;
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
            <li class="active"><a href="login.php">Belépés</a></li>
        </ul>
    </nav>

    <!-- MAIN -->
    <div id="main">

        <section class="login-wrapper">

            <div class="login-box">

                <div class="login-title">
                    <h1>Belépés</h1>
                    <p>Jelentkezz be a blogkezelő rendszer használatához.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="post">

                    <div class="field">
                        <label for="username">Felhasználónév</label>
                        <input type="text"
                               name="username"
                               id="username"
                               required>
                    </div>

                    <div class="field">
                        <label for="password">Jelszó</label>
                        <input type="password"
                               name="password"
                               id="password"
                               required>
                    </div>

                    <ul class="actions special">
                        <li>
                            <button type="submit"
                                    class="button primary large">
                                Belépés
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

</body>
</html>