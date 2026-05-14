<?php
// navbar.php – Beágyazható navigációs sáv

$isLoggedIn  = !empty($_SESSION['user_id']);
$navUsername = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';
$navRole     = $isLoggedIn ? $_SESSION['role'] : '';
$isAdmin     = $navRole === 'admin';
?>

<style>
    /* ── Session badge a nav-ban ── */
    #nav .nav-user-info {
        display: flex;
        align-items: center;
        gap: 0;
        flex-shrink: 0;
        height: 4rem;
        line-height: 4rem;
    }

    #nav .nav-username {
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 0.75rem;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
        padding: 0 0.75rem 0 1.5rem;
        border-left: solid 1px rgba(255, 255, 255, 0.2);
        white-space: nowrap;
    }

    #nav .nav-role-badge {
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 0.65rem;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        background: #18bfef;
        color: #ffffff;
        padding: 0.2rem 0.55rem;
        margin-right: 1rem;
        line-height: 1;
        align-self: center;
    }

    #nav .nav-role-badge.admin {
        background: #a855f7;
    }

    /* Nav action gombok – a téma link stílusával */
    #nav ul.links li.nav-action a {
        color: rgba(255, 255, 255, 0.85);
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    #nav ul.links li.nav-action a:hover {
        color: #18bfef !important;
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Kijelentkezés – piros hover */
    #nav ul.links li.nav-logout a:hover {
        color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.1);
    }

    /* Belépés gomb – kiemelve */
    #nav ul.links li.nav-login {
        background-color: rgba(24, 191, 239, 0.15);
        border-left: solid 2px #18bfef;
    }

    #nav ul.links li.nav-login a {
        color: #18bfef !important;
    }

    #nav ul.links li.nav-login a:hover {
        background-color: rgba(24, 191, 239, 0.25);
        color: #ffffff !important;
    }
</style>

<nav id="nav">

    <ul class="links">

        <li><a href="/WEBPROG_GYAK/vaszilijedc/index.php">Kezdőlap</a></li>
        <li><a href="/WEBPROG_GYAK/vaszilijedc/blog.php">Blog</a></li>
        <li><a href="#">Írások</a></li>
        <li><a href="/WEBPROG_GYAK/vaszilijedc/contact.php">Üzenetküldés</a></li>
        <li><a href="/WEBPROG_GYAK/vaszilijedc/support.html">Támogatás</a></li>

        <?php if ($isLoggedIn): ?>
            <!-- Bejelentkezett: Panel gomb + Kilépés -->
            <li class="nav-action">
                <a href="/WEBPROG_GYAK/vaszilijedc/user_management_api/user_manager_form.php">
                    <?= $isAdmin ? 'Felhasználók' : 'Profilom' ?>
                </a>
            </li>
            <li class="nav-action nav-logout">
                <a href="#" onclick="navLogout(); return false;">Kilépés</a>
            </li>
        <?php else: ?>
            <!-- Kijelentkezett: Belépés gomb -->
            <li class="nav-login">
                <a href="/WEBPROG_GYAK/vaszilijedc/login.php">Belépés</a>
            </li>
        <?php endif; ?>

    </ul>

    <ul class="icons">

        <?php if ($isLoggedIn): ?>
            <!-- Bejelentkezett user neve + badge az icons részen -->
            <li class="nav-user-info">
                <span class="nav-username"><?= $navUsername ?></span>
                <span class="nav-role-badge <?= $navRole ?>"><?= $isAdmin ? 'Admin' : 'User' ?></span>
            </li>
        <?php endif; ?>

        <li>
            <a href="https://facebook.com" target="_blank" class="icon brands fa-facebook-f">
                <span class="label">Facebook</span>
            </a>
        </li>
        <li>
            <a href="https://instagram.com" target="_blank" class="icon brands fa-instagram">
                <span class="label">Instagram</span>
            </a>
        </li>
        <li>
            <a href="https://youtube.com" target="_blank" class="icon brands fa-youtube">
                <span class="label">Youtube</span>
            </a>
        </li>

    </ul>

</nav>

<script>
    async function navLogout() {
        await fetch('/WEBPROG_GYAK/vaszilijedc/user_management_api/users_api.php?action=logout', {
            method: 'POST'
        });
        window.location.href = '/WEBPROG_GYAK/vaszilijedc/index.php';
    }
</script>