<?php if (!isset($showFav)) $showFav = false; ?>

<noscript>
    <div class="noscript-banner">Bitte JavaScript aktivieren, um Auto24 vollständig nutzen zu können.</div>
</noscript>

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
