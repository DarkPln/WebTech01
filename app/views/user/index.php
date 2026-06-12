<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Konto – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="home-main">
        <div class="user-dashboard">

            <div class="user-dashboard-header">
                <h1>Willkommen, <span id="display-username"></span></h1>
                <div style="display:flex; align-items:center; gap:10px;">
                    <a href="<?= BASE_URL ?>/admin" id="adminDashboardLink" class="user-admin-link" style="display:none;">Admin Dashboard</a>
                    <a href="<?= BASE_URL ?>/auth/logout" class="user-logout-link">Abmelden</a>
                </div>
            </div>

            <div class="admin-tabs">
                <button class="admin-tab active" data-target="tabProfil">Profil</button>
                <button class="admin-tab" data-target="tabNachrichten">
                    Nachrichten<span class="tab-badge" id="msgBadge" style="display:none;"></span>
                </button>
                <button class="admin-tab" data-target="tabBuchungen">Buchungen</button>
                <button class="admin-tab" data-target="tabInserate">Inserate</button>
            </div>

            <div id="tabProfil" class="admin-tab-content active">
                <div id="profileSuccess" class="success-message" style="display:none;"></div>
                <div id="profileError"   class="error-message"   style="display:none;"></div>

                <p class="user-section-title">Kontoinformationen</p>
                <form id="profileForm">
                    <div class="user-profile-grid">
                        <div>
                            <label for="username">Benutzername <span style="color:rgb(227,27,7)">*</span></label>
                            <input type="text" id="username" name="username" required autocomplete="username">
                            <span class="field-error" id="username-error"></span>
                        </div>
                        <div>
                            <label for="email">E-Mail <span style="color:#666;font-size:12px;">(optional)</span></label>
                            <input type="email" id="email" name="email" autocomplete="email" placeholder="z.B. max@muster.de">
                        </div>
                        <div>
                            <label for="phone">Telefon <span style="color:#666;font-size:12px;">(optional)</span></label>
                            <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="z.B. +49 170 1234567">
                        </div>
                        <div>
                            <label for="city">Wohnort <span style="color:#666;font-size:12px;">(optional)</span></label>
                            <input type="text" id="city" name="city" autocomplete="address-level2" placeholder="z.B. München">
                        </div>
                    </div>
                    <input type="submit" value="Profil speichern" id="profileSaveBtn">
                </form>

                <p class="user-section-title">Passwort ändern</p>
                <div id="passwordSuccess" class="success-message" style="display:none;"></div>
                <div id="passwordError"   class="error-message"   style="display:none;"></div>
                <form id="passwordForm">
                    <div class="user-profile-grid">
                        <div>
                            <label for="password">Neues Passwort <span style="color:rgb(227,27,7)">*</span></label>
                            <input type="password" id="password" name="password" autocomplete="new-password">
                            <span class="field-error" id="password-error"></span>
                        </div>
                        <div>
                            <label for="password_confirm">Passwort bestätigen <span style="color:rgb(227,27,7)">*</span></label>
                            <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
                            <span class="field-error" id="password_confirm-error"></span>
                        </div>
                    </div>
                    <input type="submit" value="Passwort ändern" id="passwordSaveBtn">
                </form>
            </div>

            <div id="tabNachrichten" class="admin-tab-content">
                <div class="msg-actions">
                    <button class="msg-mark-read-btn" id="markAllReadBtn">Alle als gelesen markieren</button>
                </div>
                <div id="messagesContainer"></div>
            </div>

            <div id="tabBuchungen" class="admin-tab-content">
                <div id="buchungenContainer"></div>
            </div>

            <div id="tabInserate" class="admin-tab-content">
                <div id="userInserate"></div>
            </div>

        </div>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>

</body>
</html>
<!-- Tim -->
