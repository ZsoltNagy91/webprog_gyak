<?php require_once 'config/init.php'; ?>
<!DOCTYPE HTML>
...
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