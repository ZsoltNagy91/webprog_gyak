<!DOCTYPE HTML>
<html lang="hu">

<head>

    <title>Belépés - Vaszilij EDC</title>

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

        <!-- Header -->

        <header id="header">

            <a href="index.php"
                class="logo">

                Vaszilij EDC

            </a>

        </header>

        <!-- Nav -->
        <?php
        include 'user_management_api/navbar.php';
        ?>
        <nav id="nav">

            <ul class="links">

                <li>
                    <a href="index.php">Kezdőlap</a>
                </li>

                <li>
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

                <li class="active">
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
        <!-- Main -->

        <div id="main">

            <section>
                <?php
                include 'user_management_api/auth_form.php';
                ?>
            </section>

        </div>

        <!-- Footer -->

        <footer id="footer">

            <section>

                <h3>Email</h3>

                <p>
                    kapcsolat@vaszilijedc.hu
                </p>

            </section>

        </footer>

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