<?php
// Használat: <?php include 'user_management_api/navbar.php'; 

$isLoggedIn = !empty($_SESSION['user_id']);
$navUsername = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';
$navRole = $isLoggedIn ? $_SESSION['role'] : '';
$navInitial = $isLoggedIn ? strtoupper(substr($navUsername, 0, 1)) : '';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Epilogue:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .nb-wrap {
        --nb-bg: rgba(8, 10, 15, .92);
        --nb-border: #1f2433;
        --nb-text: #e2e8f8;
        --nb-muted: #5a6480;
        --nb-muted2: #8892aa;
        --nb-accent: #3b82f6;
        --nb-accent2: #6366f1;
        --nb-danger: #ef4444;
        --nb-admin: #a855f7;
        --nb-user: #3b82f6;

        position: sticky;
        top: 0;
        z-index: 100;
        background: var(--nb-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--nb-border);
        font-family: 'Epilogue', sans-serif;
    }

    .nb-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: .875rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    /* Brand */
    .nb-brand {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        font-size: 1.15rem;
        letter-spacing: -.02em;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
        flex-shrink: 0;
    }

    /* Középső nav linkek helye – bővíthető */
    .nb-links {
        display: flex;
        align-items: center;
        gap: .25rem;
        flex: 1;
        padding-left: 1.5rem;
    }

    .nb-link {
        color: var(--nb-muted2);
        text-decoration: none;
        font-size: .875rem;
        font-weight: 500;
        padding: .4rem .75rem;
        border-radius: 7px;
        transition: color .15s, background .15s;
    }

    .nb-link:hover {
        color: var(--nb-text);
        background: rgba(255, 255, 255, .05);
    }

    .nb-link.active {
        color: var(--nb-text);
        background: rgba(59, 130, 246, .1);
    }

    /* Jobb oldal */
    .nb-right {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-shrink: 0;
    }

    /* Badge */
    .nb-badge {
        display: inline-flex;
        align-items: center;
        padding: .2rem .55rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .nb-badge-admin {
        background: rgba(168, 85, 247, .15);
        color: var(--nb-admin);
        border: 1px solid rgba(168, 85, 247, .25);
    }

    .nb-badge-user {
        background: rgba(59, 130, 246, .12);
        color: var(--nb-user);
        border: 1px solid rgba(59, 130, 246, .2);
    }

    /* Avatar */
    .nb-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: .8rem;
        color: #fff;
        flex-shrink: 0;
    }

    .nb-username {
        font-size: .875rem;
        font-weight: 500;
        color: var(--nb-muted2);
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Gombok */
    .nb-btn {
        font-family: 'Epilogue', sans-serif;
        font-size: .825rem;
        font-weight: 600;
        padding: .48rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: opacity .15s, background .15s, border-color .15s, color .15s, box-shadow .15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border: none;
        white-space: nowrap;
    }

    .nb-btn-ghost {
        background: transparent;
        border: 1px solid var(--nb-border);
        color: var(--nb-muted2);
    }

    .nb-btn-ghost:hover {
        border-color: #2a3048;
        color: var(--nb-text);
    }

    .nb-btn-primary {
        background: var(--nb-accent);
        color: #fff;
        box-shadow: 0 2px 10px rgba(59, 130, 246, .25);
    }

    .nb-btn-primary:hover {
        opacity: .88;
        box-shadow: 0 4px 14px rgba(59, 130, 246, .35);
    }

    .nb-btn-panel {
        background: rgba(99, 102, 241, .12);
        border: 1px solid rgba(99, 102, 241, .25);
        color: #a5b4fc;
    }

    .nb-btn-panel:hover {
        background: rgba(99, 102, 241, .2);
        border-color: rgba(99, 102, 241, .4);
        color: #c7d2fe;
    }

    .nb-btn-logout {
        background: transparent;
        border: 1px solid var(--nb-border);
        color: var(--nb-muted2);
    }

    .nb-btn-logout:hover {
        border-color: var(--nb-danger);
        color: var(--nb-danger);
    }

    /* Elválasztó */
    .nb-divider {
        width: 1px;
        height: 20px;
        background: var(--nb-border);
        flex-shrink: 0;
    }

    /* Reszponzív */
    @media (max-width: 600px) {
        .nb-inner {
            padding: .75rem 1rem;
        }

        .nb-links {
            display: none;
        }

        .nb-username {
            display: none;
        }

        .nb-badge {
            display: none;
        }
    }
</style>

<nav class="nb-wrap">
    <div class="nb-inner">

        <!-- Brand / logó -->
        <a href="index.php" class="nb-brand">⬡ Vaszilij EDC</a>

        <!-- Középső linkek – ide vehetsz fel saját oldalakat -->
        <div class="nb-links">
            <a href="index.php" class="nb-link">Főoldal</a>
        </div>

        <!-- Jobb oldali blokk -->
        <div class="nb-right">

            <?php if ($isLoggedIn): ?>
                <!-- BEJELENTKEZETT állapot -->

                <!-- Avatar + név + badge -->
                <div class="nb-avatar"><?= $navInitial ?></div>
                <span class="nb-username"><?= $navUsername ?></span>
                <span class="nb-badge nb-badge-<?= $navRole ?>"><?= $navRole === 'admin' ? 'Admin' : 'User' ?></span>

                <div class="nb-divider"></div>

                <!-- User Manager gomb -->
                <a href="user_manager_form.php" class="nb-btn nb-btn-panel">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    <?= $navRole === 'admin' ? 'Felhasználók' : 'Profilom' ?>
                </a>

                <!-- Kijelentkezés gomb -->
                <button class="nb-btn nb-btn-logout" onclick="nbLogout()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Kilépés
                </button>

            <?php else: ?>
                <!-- KIJELENTKEZETT állapot -->

                <a href="login.php" class="nb-btn nb-btn-ghost">Bejelentkezés</a>
                <a href="login.php?tab=register" class="nb-btn nb-btn-primary">Regisztráció</a>

            <?php endif; ?>

        </div>
    </div>
</nav>

<script>
    async function nbLogout() {
        await fetch('users_api.php?action=logout', {
            method: 'POST'
        });
        window.location.href = 'index.php';
    }
</script>