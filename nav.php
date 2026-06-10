<?php if (!isset($showFav)) $showFav = false; ?>

<noscript>
    <div style="position:fixed;inset:0;z-index:99999;background:rgba(10,10,10,0.97);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px;font-family:'DM Sans',sans-serif;color:#fff;text-align:center;padding:2rem;">
        <div style="font-size:52px;line-height:1;">⚠</div>
        <div style="font-size:26px;font-weight:700;letter-spacing:0.5px;">JavaScript ist deaktiviert</div>
        <div style="font-size:15px;color:#aaa;max-width:460px;line-height:1.7;">
            <strong style="color:#fff;">Auto24</strong> benötigt JavaScript für alle Funktionen —
            Login, Buchungen, Merkliste und mehr.<br>
            Bitte aktiviere JavaScript in deinen Browser-Einstellungen und lade die Seite neu.
        </div>
        <div style="margin-top:12px;border:1px solid rgba(227,27,27,0.5);background:rgba(227,27,27,0.08);color:rgb(227,27,27);font-size:11px;letter-spacing:2.5px;text-transform:uppercase;padding:6px 20px;border-radius:20px;">
            Auto24 · Fahrzeugbörse
        </div>
    </div>
</noscript>

<nav>
    <a href="index.php" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="gebrauchtwagenList.php">Auto kaufen</a></li>
        <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
        <?php if ($showFav): ?>
        <button class="nFav-btn" id="nFavBtn" onclick="togglePanel()">
            <span class="navFav-label">♡ Merkliste</span>
            <span class="navFav-count" id="favCount" style="display:none">0</span>
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
    <div class="fav-list-footer" id="favListFooter" style="display:none">
        <div class="total-cost-row">
            <span class="total-cost-label">Gesamtkosten:</span>
            <span class="total-cost-value" id="totalCostValue">0 €</span>
        </div>
        <button class="clear-fav-list-btn" id="clearFavListBtn">Favoriten leeren</button>
        <a href="merkliste.php" class="open-full-favs-btn">Ganze Merkliste öffnen</a>
    </div>
</div>
<?php endif; ?>
