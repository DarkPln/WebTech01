<?php if (!isset($showFav)) $showFav = false; ?>

<?php
// Cookie wird serverseitig gelesen und sofort per Inline-Script gesetzt,
// damit der Light-Mode OHNE JavaScript-Flash angewendet wird.
// localStorage kann das nicht — es ist erst nach dem DOM-Aufbau lesbar.
$lightModeCookie = !empty($_COOKIE['auto24_lightMode']) && $_COOKIE['auto24_lightMode'] === '1';
?>
<?php if ($lightModeCookie): ?>
<script>document.body.classList.add('light-mode');</script>
<?php endif; ?>

<noscript>
    <div class="noscript-banner">Bitte JavaScript aktivieren, um Auto24 vollständig nutzen zu können.</div>
</noscript>

<!-- Warnung wenn Cookies im Browser deaktiviert sind (wird per JS eingeblendet) -->
<div id="cookieDisabledBanner" class="cookie-disabled-banner" style="display:none;">
    &#9888; Cookies sind in Ihrem Browser deaktiviert. Bitte aktivieren Sie Cookies, um Auto24 vollständig nutzen zu können (z.&nbsp;B. für den Login und Ihre Einstellungen).
</div>

<!-- Cookie-Consent-Banner (wird per JS eingeblendet wenn noch keine Entscheidung getroffen) -->
<div id="cookieConsentBanner" class="cookie-consent-banner" style="display:none;">
    <div class="cookie-consent-text">
        <strong>Cookie-Hinweis</strong>
        Wir verwenden Cookies, um Ihre Einstellungen (z.&nbsp;B. Anzeigemodus) zu speichern und die Nutzung unserer Website zu verbessern. Sie können Cookies jederzeit in Ihren Browsereinstellungen deaktivieren.
    </div>
    <div class="cookie-consent-actions">
        <button class="cookie-btn cookie-btn--accept" onclick="acceptCookies()">Akzeptieren</button>
        <button class="cookie-btn cookie-btn--decline" onclick="declineCookies()">Ablehnen</button>
    </div>
</div>

<nav>
    <a href="<?= BASE_URL ?>/" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="<?= BASE_URL ?>/cars">Auto kaufen</a></li>
        <li><a href="<?= BASE_URL ?>/listings/sell">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="<?= BASE_URL ?>/auth/login" class="nav-auth-link" id="navAuthLink">Login</a>
        <?php if ($showFav): ?>
        <button class="nFav-btn" id="nFavBtn" onclick="togglePanel()">
            <span class="navFav-label">♡ Merkliste</span>
            <span class="navFav-count" id="favCount">0</span>
        </button>
        <?php endif; ?>
        <button class="mode-btn" onclick="toggleMode()">Light</button>
    </div>
</nav>

<?php if ($showFav): ?>
<div class="fav-ovl" id="favOvl" onclick="togglePanel()"></div>
<div class="fav-list" id="favList">
    <div class="fav-list-header">
        <div class="fav-list-title">Merkliste</div>
        <div class="fav-list-dsc" id="favListCount">0 Fahrzeuge</div>
        <button class="fav-list-close-btn" onclick="togglePanel()">✕</button>
    </div>
    <div class="fav-list-body" id="favListBody">
        <div class="fav-list-empty" id="favListEmpty">
            <div class="fav-empty-heart">♡</div>
            <div class="fav-empty-title">Merkliste ist leer</div>
            <div class="fav-empty-desc">Füge Fahrzeuge über das Herz-Symbol hinzu.</div>
        </div>
        <div id="favItems"></div>
    </div>
    <div class="fav-list-footer" id="favListFooter">
        <div class="total-cost-row">
            <span class="total-cost-label">Gesamtkosten:</span>
            <span class="total-cost-value" id="totalCostValue">0 €</span>
        </div>
        <button class="clear-fav-list-btn" id="clearFavListBtn">Favoriten leeren</button>
        <a href="<?= BASE_URL ?>/merkliste" class="open-full-favs-btn">Ganze Merkliste öffnen</a>
    </div>
</div>
<?php endif; ?>
