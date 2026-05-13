<?php
if (!empty($_SESSION['user_id'])) return;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .af-wrap {
        --af-bg: #0f0f11;
        --af-surface: #18181c;
        --af-border: #2e2e36;
        --af-accent: #7c6dfa;
        --af-accent2: #a78bfa;
        --af-text: #e8e8f0;
        --af-muted: #6b6b80;
        --af-error: #f87171;
        --af-success: #4ade80;
        --af-radius: 14px;
        --af-font: 'DM Sans', sans-serif;
        --af-serif: 'DM Serif Display', serif;

        font-family: var(--af-font);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem;
    }

    .af-card {
        background: var(--af-surface);
        border: 1px solid var(--af-border);
        border-radius: 24px;
        width: 100%;
        max-width: 420px;
        padding: 2.5rem 2.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .af-card::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(124, 109, 250, 0.18) 0%, transparent 70%);
        pointer-events: none;
    }

    .af-header {
        margin-bottom: 2rem;
    }

    .af-title {
        font-family: var(--af-serif);
        font-size: 1.75rem;
        color: var(--af-text);
        margin: 0 0 0.25rem;
        line-height: 1.2;
    }

    .af-subtitle {
        font-size: .875rem;
        color: var(--af-muted);
        margin: 0;
    }

    .af-toggle {
        display: flex;
        background: var(--af-bg);
        border: 1px solid var(--af-border);
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 1.75rem;
        gap: 4px;
    }

    .af-toggle button {
        flex: 1;
        padding: .55rem 0;
        border: none;
        border-radius: 7px;
        font-family: var(--af-font);
        font-size: .875rem;
        font-weight: 500;
        cursor: pointer;
        transition: background .2s, color .2s, box-shadow .2s;
        background: transparent;
        color: var(--af-muted);
    }

    .af-toggle button.active {
        background: var(--af-accent);
        color: #fff;
        box-shadow: 0 2px 12px rgba(124, 109, 250, .35);
    }

    .af-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .af-field {
        display: flex;
        flex-direction: column;
        gap: .4rem;
    }

    .af-label {
        font-size: .8rem;
        font-weight: 600;
        color: var(--af-muted);
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .af-input {
        background: var(--af-bg);
        border: 1px solid var(--af-border);
        border-radius: var(--af-radius);
        color: var(--af-text);
        font-family: var(--af-font);
        font-size: .95rem;
        padding: .75rem 1rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
        box-sizing: border-box;
    }

    .af-input:focus {
        border-color: var(--af-accent);
        box-shadow: 0 0 0 3px rgba(124, 109, 250, .18);
    }

    .af-input::placeholder {
        color: var(--af-muted);
    }

    .af-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b6b80' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .af-field.af-hidden {
        display: none;
    }

    .af-submit {
        background: var(--af-accent);
        color: #fff;
        border: none;
        border-radius: var(--af-radius);
        font-family: var(--af-font);
        font-size: 1rem;
        font-weight: 600;
        padding: .85rem;
        cursor: pointer;
        margin-top: .5rem;
        transition: background .2s, transform .1s, box-shadow .2s;
        box-shadow: 0 4px 16px rgba(124, 109, 250, .3);
        position: relative;
    }

    .af-submit:hover {
        background: var(--af-accent2);
        box-shadow: 0 6px 20px rgba(124, 109, 250, .45);
    }

    .af-submit:active {
        transform: scale(.98);
    }

    .af-submit:disabled {
        opacity: .6;
        cursor: not-allowed;
        transform: none;
    }

    .af-msg {
        display: none;
        padding: .75rem 1rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 500;
        margin-top: .25rem;
    }

    .af-msg.error {
        display: block;
        background: rgba(248, 113, 113, .1);
        border: 1px solid rgba(248, 113, 113, .3);
        color: var(--af-error);
    }

    .af-msg.success {
        display: block;
        background: rgba(74, 222, 128, .1);
        border: 1px solid rgba(74, 222, 128, .3);
        color: var(--af-success);
    }

    .af-spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, .3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: af-spin .7s linear infinite;
        margin: 0 auto;
    }

    @keyframes af-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .af-submit.loading .af-btn-text {
        display: none;
    }

    .af-submit.loading .af-spinner {
        display: block;
    }

    .af-footer {
        text-align: center;
        margin-top: 1.25rem;
        font-size: .85rem;
        color: var(--af-muted);
    }

    .af-footer a {
        color: var(--af-accent2);
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }

    .af-footer a:hover {
        text-decoration: underline;
    }

    @keyframes af-fade-in {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .af-form {
        animation: af-fade-in .25s ease;
    }
</style>

<div class="af-wrap">
    <div class="af-card">

        <div class="af-header">
            <h2 class="af-title" id="af-title">Üdvözlünk</h2>
            <p class="af-subtitle" id="af-subtitle">Jelentkezz be a fiókodba</p>
        </div>

        <div class="af-toggle">
            <button id="af-btn-login" class="active" onclick="afSwitch('login')">Bejelentkezés</button>
            <button id="af-btn-register" onclick="afSwitch('register')">Regisztráció</button>
        </div>

        <div class="af-msg" id="af-msg"></div>

        <form class="af-form" id="af-form" onsubmit="afSubmit(event)">

            <div class="af-field">
                <label class="af-label" for="af-username">Felhasználónév</label>
                <input class="af-input" type="text" id="af-username"
                    name="username" placeholder="pl. kovacs_peter"
                    autocomplete="username" required>
            </div>

            <div class="af-field">
                <label class="af-label" for="af-password">Jelszó</label>
                <input class="af-input" type="password" id="af-password"
                    name="password" placeholder="••••••••"
                    autocomplete="current-password" required>
            </div>

            <div class="af-field af-hidden" id="af-field-confirm">
                <label class="af-label" for="af-confirm">Jelszó megerősítése</label>
                <input class="af-input" type="password" id="af-confirm"
                    name="confirm" placeholder="••••••••"
                    autocomplete="new-password">
            </div>

            <div class="af-field af-hidden" id="af-field-role">
                <label class="af-label" for="af-role">Szerepkör</label>
                <select class="af-input af-select" id="af-role" name="role">
                    <option value="user">Felhasználó</option>
                    <option value="admin">Adminisztrátor</option>
                </select>
            </div>

            <button type="submit" class="af-submit" id="af-submit">
                <span class="af-btn-text" id="af-btn-text">Bejelentkezés</span>
                <span class="af-spinner"></span>
            </button>

        </form>

        <div class="af-footer" id="af-footer">
            Még nincs fiókod? <a onclick="afSwitch('register')">Regisztrálj most</a>
        </div>

    </div>
</div>

<script>
    (function() {
        const API_URL = 'user_management_api/users_api.php';
        let afMode = 'login';

        // Ha az URL-ben ?tab=register van, rögtön regisztrációs módra vált
        if (new URLSearchParams(window.location.search).get('tab') === 'register') {
            document.addEventListener('DOMContentLoaded', () => afSwitch('register'));
        }

        window.afSwitch = function(mode) {
            afMode = mode;
            const isReg = mode === 'register';

            document.getElementById('af-btn-login').classList.toggle('active', !isReg);
            document.getElementById('af-btn-register').classList.toggle('active', isReg);

            document.getElementById('af-title').textContent = isReg ? 'Hozz létre fiókot' : 'Üdvözlünk';
            document.getElementById('af-subtitle').textContent = isReg ? 'Regisztrálj ingyenesen' : 'Jelentkezz be a fiókodba';

            document.getElementById('af-field-confirm').classList.toggle('af-hidden', !isReg);
            document.getElementById('af-field-role').classList.toggle('af-hidden', !isReg);

            document.getElementById('af-btn-text').textContent = isReg ? 'Regisztráció' : 'Bejelentkezés';
            document.getElementById('af-password').setAttribute('autocomplete', isReg ? 'new-password' : 'current-password');

            document.getElementById('af-footer').innerHTML = isReg ?
                'Már van fiókod? <a onclick="afSwitch(\'login\')">Jelentkezz be</a>' :
                'Még nincs fiókod? <a onclick="afSwitch(\'register\')">Regisztrálj most</a>';

            afMsg('', '');
            document.getElementById('af-form').reset();

            const form = document.getElementById('af-form');
            form.style.animation = 'none';
            requestAnimationFrame(() => {
                form.style.animation = '';
            });
        };

        window.afSubmit = async function(e) {
            e.preventDefault();
            afMsg('', '');

            const username = document.getElementById('af-username').value.trim();
            const password = document.getElementById('af-password').value;
            const btn = document.getElementById('af-submit');

            if (!username || !password) return afMsg('Minden mező kitöltése kötelező.', 'error');

            if (afMode === 'register') {
                const confirm = document.getElementById('af-confirm').value;
                if (password !== confirm) return afMsg('A két jelszó nem egyezik.', 'error');
                if (password.length < 6) return afMsg('A jelszó legalább 6 karakter legyen.', 'error');
            }

            btn.disabled = true;
            btn.classList.add('loading');

            const action = afMode === 'login' ? 'login' : 'register';
            const payload = {
                username,
                password
            };
            if (afMode === 'register') payload.role = document.getElementById('af-role').value;

            try {
                const res = await fetch(`${API_URL}?action=${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (res.ok) {
                    if (afMode === 'login') {
                        afMsg('Sikeres bejelentkezés!', 'success');
                        // Oldal újratöltés → PHP újra fut → navbar frissül, form eltűnik
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        setTimeout(() => {
                            afSwitch('login');
                            afMsg('Sikeresen regisztráltál! Most már bejelentkezhetsz.', 'success');
                        }, 1000);
                    }
                } else {
                    afMsg(data.error || 'Ismeretlen hiba.', 'error');
                }
            } catch {
                afMsg('Nem sikerült csatlakozni a szerverhez.', 'error');
            } finally {
                btn.disabled = false;
                btn.classList.remove('loading');
            }
        };

        function afMsg(text, type) {
            const el = document.getElementById('af-msg');
            el.textContent = text;
            el.className = 'af-msg' + (type ? ' ' + type : '');
        }
    })();
</script>