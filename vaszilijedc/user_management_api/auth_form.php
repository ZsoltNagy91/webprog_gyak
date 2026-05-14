<?php
if (!empty($_SESSION['user_id'])) return;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .af-wrap {
        font-family: "Merriweather", Georgia, serif;
        font-weight: 300;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem 4rem;
    }

    .af-card {
        background: #ffffff;
        width: 100%;
        max-width: 480px;
        padding: 3rem;
        position: relative;
    }

    /* Felső kék csík – a téma button/accent színével */
    .af-card::before {
        content: '';
        display: block;
        height: 4px;
        background: #18bfef;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
    }

    /* ── Fejléc ── */
    .af-header {
        margin-bottom: 2rem;
        border-bottom: solid 2px #eeeeee;
        padding-bottom: 1.5rem;
    }

    .af-title {
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 1.75rem;
        line-height: 1.3;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        color: #212931;
        margin: 0 0 0.4rem;
    }

    .af-subtitle {
        font-size: 0.85rem;
        color: #909498;
        margin: 0;
        line-height: 1.6;
    }

    /* ── Toggle gombok ── */
    .af-toggle {
        display: flex;
        gap: 0;
        margin-bottom: 2rem;
        border: solid 2px #eeeeee;
    }

    .af-toggle button {
        flex: 1;
        padding: 0.65rem 0;
        border: none;
        background: transparent;
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 0.8rem;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        color: #909498;
        cursor: pointer;
        transition: background 0.2s ease-in-out, color 0.2s ease-in-out;
        border-right: solid 2px #eeeeee;
    }

    .af-toggle button:last-child {
        border-right: none;
    }

    .af-toggle button.active {
        background: #18bfef;
        color: #ffffff;
    }

    .af-toggle button:hover:not(.active) {
        background: #f4f4f4;
        color: #212931;
    }

    /* ── Form mezők ── */
    .af-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .af-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .af-field.af-hidden {
        display: none;
    }

    .af-label {
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 0.75rem;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        color: #212931;
    }

    .af-input {
        background: #ffffff;
        border: solid 2px #eeeeee;
        color: #212931;
        font-family: "Merriweather", Georgia, serif;
        font-weight: 300;
        font-size: 0.9rem;
        padding: 0.65rem 1rem;
        outline: none;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .af-input:focus {
        border-color: #18bfef;
        box-shadow: 0 0 0 2px rgba(24, 191, 239, 0.15);
    }

    .af-input::placeholder {
        color: #c0c4c8;
    }

    .af-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23909498' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    /* ── Submit gomb – a téma .button stílusával ── */
    .af-submit {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.85rem 2rem;
        background-color: transparent;
        border: solid 3px #212931;
        color: #212931;
        font-family: "Source Sans Pro", Helvetica, sans-serif;
        font-weight: 900;
        font-size: 0.8rem;
        letter-spacing: 0.225em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out, border-color 0.2s ease-in-out;
        position: relative;
    }

    .af-submit:hover {
        background-color: #18bfef;
        border-color: #18bfef;
        color: #ffffff;
    }

    .af-submit:active {
        background-color: #10a8d4;
        border-color: #10a8d4;
        color: #ffffff;
    }

    .af-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Loading állapot */
    .af-submit.loading .af-btn-text {
        visibility: hidden;
    }

    .af-spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 18px;
        height: 18px;
        border: 2px solid rgba(33, 41, 49, 0.2);
        border-top-color: #212931;
        border-radius: 50%;
        animation: af-spin 0.7s linear infinite;
    }

    .af-submit.loading .af-spinner {
        display: block;
    }

    .af-submit:hover .af-spinner,
    .af-submit.loading:hover .af-spinner {
        border-top-color: #ffffff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    @keyframes af-spin {
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* ── Üzenetek ── */
    .af-msg {
        display: none;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        font-weight: 300;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .af-msg.error {
        display: block;
        background: rgba(231, 76, 60, 0.08);
        border-left: solid 4px #e74c3c;
        color: #c0392b;
    }

    .af-msg.success {
        display: block;
        background: rgba(24, 191, 239, 0.08);
        border-left: solid 4px #18bfef;
        color: #0d8cad;
    }

    /* ── Lábléc link ── */
    .af-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: solid 2px #eeeeee;
        font-size: 0.8rem;
        color: #909498;
        line-height: 1.6;
    }

    .af-footer a {
        color: #18bfef;
        border-bottom: dotted 1px rgba(24, 191, 239, 0.5);
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s ease-in-out, border-color 0.2s ease-in-out;
    }

    .af-footer a:hover {
        color: #10a8d4;
        border-bottom-color: transparent;
    }

    /* ── Fade animáció ── */
    @keyframes af-fade-in {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .af-form {
        animation: af-fade-in 0.2s ease;
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
        const API_URL = '/WEBPROG_GYAK/vaszilijedc/user_management_api/users_api.php';
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