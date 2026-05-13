<?php
$success = isset($_GET['success']);
?>
<!DOCTYPE HTML>
<html lang="hu">

<head>
    <meta charset="utf-8" />
    <title>Üzenetküldés - Vaszilij EDC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="assets/css/main.css" />

    <style>
        #main {
            display: flex;
            justify-content: center;
            padding: 4rem 0;
        }

        .contact-wrapper {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 5rem;
        }

        .page-title h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .page-title p {
            font-size: 1.2rem;
            color: #666;
            text-align: center;
        }

        .success-message {
            margin-top: 1.5rem;
            padding: 1rem 1.5rem;
            background: #eaf7ea;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            display: inline-block;
            font-weight: 600;
        }

        .contact-grid {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 120px;
            width: 100%;
            margin: 0 auto;
        }

        .contact-info,
        .contact-form {
            width: 450px;
        }

        .contact-info h2,
        .contact-form h2 {
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .contact-info p {
            line-height: 2;
            margin-bottom: 1.5rem;
        }

        .map-section {
            margin-top: 2rem;
        }

        .map-section iframe {
            width: 100%;
            height: 350px;
            border: 0;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .contact-form form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .contact-form textarea {
            min-height: 200px;
            resize: vertical;
        }

        .contact-form button {
            width: 220px !important;
            height: 60px !important;

            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            margin: 0 auto !important;

            border: none !important;
            background: #1f1f1f !important;

            color: #ffffff !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            letter-spacing: 2px !important;
            text-transform: uppercase !important;

            cursor: pointer !important;
            transition: 0.3s !important;
            border-radius: 5px !important;
        }

        .contact-form button:hover {
            background: #e74c3c !important;
            color: #ffffff !important;
        }

        @media screen and (max-width: 900px) {
            .contact-grid {
                flex-direction: column;
                align-items: center;
                gap: 4rem;
            }

            .contact-info,
            .contact-form {
                width: 100%;
                max-width: 500px;
            }

            .page-title h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body class="is-preload">

    <div id="wrapper">

        <!-- Header -->
        <header id="header">
            <a href="index.html" class="logo">VASZILIJ EDC</a>
        </header>

        <!-- NAV -->
        <nav id="nav">

            <ul class="links">

                <li>
                    <a href="index.html">Kezdőlap</a>
                </li>

                <li>
                    <a href="blog.php">Blog</a>
                </li>

                <li>
                    <a href="articles.html">Írások</a>
                </li>

                <li class="active">
                    <a href="contact.php">Üzenetküldés</a>
                </li>

                <li>
                    <a href="support.html">Támogatás</a>
                </li>

                <li>
                    <a href="login.php">Belépés</a>
                </li>

            </ul>

            <ul class="icons">

                <li>
                    <a href="https://facebook.com" target="_blank"
                        class="icon brands fa-facebook-f">
                        <span class="label">Facebook</span>
                    </a>
                </li>

                <li>
                    <a href="https://instagram.com" target="_blank"
                        class="icon brands fa-instagram">
                        <span class="label">Instagram</span>
                    </a>
                </li>

                <li>
                    <a href="https://youtube.com" target="_blank"
                        class="icon brands fa-youtube">
                        <span class="label">Youtube</span>
                    </a>
                </li>

            </ul>

        </nav>

        <!-- MAIN -->
        <div id="main">

            <section class="contact-wrapper">

                <div class="page-title">
                    <h1>ÜZENETKÜLDÉS</h1>
                    <p>Lépj kapcsolatba velem vagy a közösséggel.</p>

                    <?php if ($success): ?>
                        <div class="success-message">
                            Az üzenetet sikeresen elküldtük.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="contact-grid">

                    <!-- BAL OLDAL -->
                    <div class="contact-info">

                        <h2>KAPCSOLAT</h2>

                        <p>
                            Vaszilij EDC közösség és blog.
                        </p>

                        <p>
                            Email:<br>
                            kapcsolat@vaszilijedc.hu
                        </p>

                        <p>
                            Kecskemét, Magyarország
                        </p>

                        <!-- TÉRKÉP -->
                        <div class="map-section">

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d810.5515450339254!2d19.66748336186682!3d46.8956467423111!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4743da7a6c479e1d%3A0xc8292b3f6dc69e7f!2sNeumann%20J%C3%A1nos%20Egyetem%20GAMF%20M%C5%B1szaki%20%C3%A9s%20Informatikai%20Kar!5e0!3m2!1shu!2shu!4v1778173901005!5m2!1shu!2shu"
                                allowfullscreen=""
                                loading="lazy">
                            </iframe>

                        </div>

                    </div>

                    <!-- JOBB OLDAL -->
                    <div class="contact-form">

                        <h2>ÍRJ ÜZENETET</h2>

                        <form action="save_message.php" method="post">

                            <input type="text"
                                name="name"
                                placeholder="Teljes név"
                                required>

                            <input type="email"
                                name="email"
                                placeholder="Email cím"
                                required>

                            <input type="text"
                                name="subject"
                                placeholder="Tárgy">

                            <textarea name="message"
                                placeholder="Írd ide az üzeneted..."
                                required></textarea>

                            <button type="submit">
                                Küldés
                            </button>

                        </form>

                    </div>

                </div>

            </section>

        </div>

        <!-- Footer -->
        <footer id="footer">

            <section>
                <h3>Vaszilij EDC</h3>
                <p>
                    EDC • Kések • Outdoor • Felszerelések
                </p>
            </section>

        </footer>

    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>