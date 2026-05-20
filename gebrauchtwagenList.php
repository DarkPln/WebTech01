<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset = "UTF-8">
    <title> Gebrauchtwagen Liste</title>
    <link rel="stylesheet" href="mystyle.css">
</head>

<!-- Lukas: Navigation -->
<body>
    <?php
        $pageHeadline   = "GEBRAUCHTWAGEN";
        $pageIntro      = "Hier finden Sie verschiedene Arten von Gebrauchtwagen.";
        $financeHeadline = "Kauffinanzierung";
        $financeHint    = "Geben Sie den gewünschten Finanzierungsbetrag ein, um die monatlichen Raten zu berechnen (5% Zins).";
        $loanTermHint   = "Geben Sie die gewünschte Laufzeit in Monaten ein (12-48):";
    ?>

    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="about.php">Auto verkaufen</a></li>
        </ul>

        <div class="nav-right">
            <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
            <!-- Lukas: Fav -->
            <button class="nFav-btn" id="nFavBtn" onclick="togglePanel()">
                <span class="navFav-label"> ♡ Merkliste </span>
                <span class="navFav-count" id="favCount" style="display: none">0</span>
            </button>
            <button class="mode-btn" onclick="toggleMode()">Light</button>
        </div>
    </nav>

    <div class = "fav-ovl" id="favOvl" onclick = "togglePanel()"></div>

    <!-- Lukas: Favoritenliste / warenkorb -->
    <div class = "fav-list" id="favList" >
        <div class = "fav-list-header">
            <div class = "fav-list-title">Merkliste </div>
            <div class = "fav-list-dsc" id="favListCount">0 Fahrzeuge in den Favoriten</div>
            <button class = "fav-list-close-btn" onclick= "togglePanel()">✕</button>
        </div>

        <div class = "fav-list-body" id="favListBody">
        <div class = "fav-list-empty" id="favListEmpty">Keine Fahrzeuge in der Merkliste.
            <div class = "fav-empty-heart">♡</div>
            <div class ="fav-empty-title">Ihre Merkliste ist leer</div>
            <div class = "fav-empty-desc">Fügen Sie Fahrzeuge zu Ihren Favoriten hinzu, um sie hier zu sehen.</div>
        </div>
            <div id="favItems"></div>
        </div>

        <div class = "fav-list-footer" id = "favListFooter" style = "display: none">
            <div class = "total-cost-row">
                <span class = "total-cost-label">Gesamtkosten: </span>
                <span class = "total-cost-value" id="totalCostValue">0 € </span>
            </div>
            <button class ="clear-fav-list-btn" id="clearFavListBtn">Favoriten leeren</button>
        </div>
    </div>
    <!-- Lukas: Ende Favs -->

    <h1><?php echo $pageHeadline; ?></h1>
    <p><?php echo $pageIntro; ?></p>

    <!-- FAHRZEUGE GRID -->
    <div class="layout-buttons">
        <button onclick="setVerticalLayout()">Smartphone Layout</button>
        <button onclick="setHorizontalLayout()">Desktop Layout</button>
    </div>
<div id = "carLayout" class="cars-grid horizontal-layout">

    <!-- Card 1 -->
    <div class="car-card" data-id="audi_a8_001" data-make="Audi" data-model="A8L" data-year="2024" data-fuel="Benzin" data-body="Limousine" data-power="510 PS" data-km="40000 km" data-drive="S-Line Quattro" data-price="89900">
        <div class="car-image">
            <span class="car-badge-used">Gebraucht</span>
            <!-- Klick auf das Herz wird in validation.js behandelt: Event-Listener (.car-fav) ruft `toggleFavorite(btn)` auf -->
            <button class="car-fav" type="button" >♡</button>
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg/1920px-Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg" alt="Audi A8" height = 200 width = 400
                alt="Audi A8L"
                class="car-img"
            >
        </div>
        <div class="car-body">
            <div class="car-make">AUDI</div>
            <div class="car-model">A8L</div>
            <div class="car-year">2024 · Benzin · Limousine</div>
            <div class="car-specs">
                <div class="car-spec">
                    <div class="car-spec-val">510 PS</div>
                    <div class="car-spec-key">Leistung</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">40.000 km</div>
                    <div class="car-spec-key">Kilometerstand</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">S-Line Quattro</div>
                    <div class="car-spec-key">Antrieb</div>
                </div>
            </div>
            <div class="car-footer">
                <div>
                    <div class="car-price">89.900 €</div>
                    <div class="car-price-note">inkl. MwSt.</div>
                </div>
                <a href = "audi_a8_001.php"><button class="car-btn">Details</button></a>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="car-card">
        <div class="car-image">
            <span class="car-badge-used">Gebraucht</span>
            <button class="car-fav" type="button">♡</button>
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Audi_SQ7_FL_IMG_3595.jpg/1920px-Audi_SQ7_FL_IMG_3595.jpg" alt="Audi A8" height = 220 width = 400
                alt="Audi SQ7"
                class="car-img"
            >
        </div>
        <div class="car-body">
            <div class="car-make">AUDI</div>
            <div class="car-model">SQ7</div>
            <div class="car-year">2021 · Benzin · SUV</div>
            <div class="car-specs">
                <div class="car-spec">
                    <div class="car-spec-val">250 PS</div>
                    <div class="car-spec-key">Leistung</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">28.400 km</div>
                    <div class="car-spec-key">Kilometerstand</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">Quattro</div>
                    <div class="car-spec-key">Antrieb</div>
                </div>
            </div>
            <div class="car-footer">
                <div>
                    <div class="car-price">75.000 €</div>
                    <div class="car-price-note">inkl. MwSt.</div>
                </div>
                <button class="car-btn">Details</button>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="car-card">
        <div class="car-image">
            <span class="car-badge-used">Gebraucht</span>
            <button class="car-fav" type="button">♡</button>
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Mercedes-Benz_C_220_BlueTEC_Exclusive_%28W_205%29_%E2%80%93_Frontansicht%2C_12._Juli_2014%2C_D%C3%BCsseldorf.jpg/1920px-Mercedes-Benz_C_220_BlueTEC_Exclusive_%28W_205%29_%E2%80%93_Frontansicht%2C_12._Juli_2014%2C_D%C3%BCsseldorf.jpg" alt="Mercedes-Benz C 220" height = 220 width = 420
                alt="Mercedes-Benz C 220"
                class="car-img"
            >
        </div>
        <div class="car-body">
            <div class="car-make">MERCEDES-BENZ</div>
            <div class="car-model">C 220</div>
            <div class="car-year">2024 · Benzin · Limousine</div>
            <div class="car-specs">
                <div class="car-spec">
                    <div class="car-spec-val">510 PS</div>
                    <div class="car-spec-key">Leistung</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">60.000 km</div>
                    <div class="car-spec-key">Kilometerstand</div>
                </div>
                <div class="car-spec">
                    <div class="car-spec-val">S-Tronic</div>
                    <div class="car-spec-key">Antrieb</div>
                </div>
            </div>
            <div class="car-footer">
                <div>
                    <div class="car-price">69.900 €</div>
                    <div class="car-price-note">inkl. MwSt.</div>
                </div>
                <button class="car-btn">Details</button>
            </div>
        </div>
    </div>
</div>

<!--Niclas: Funktionen Kauffinanzierung-->
<h2><?php echo $financeHeadline; ?></h2>
<label>Preis ohne Steuer:</label>
<input type="number" id="priceInput">
<button onclick="calculatePrice()">Berechnen</button>
<p id="priceWithoutTax"></p>
<p id="priceWithTax"></p>

<label>Auto24 Bank - Finanzierungshilfe:</label>
<p><?php echo $financeHint; ?></p>
<input type="number" id="financingInput">
<p><?php echo $loanTermHint; ?></p>
<input type="number" id="loanTermInput">
<button onclick="calculateFinancing()">Berechnen</button>
<p id="financingResult"></p>
<p id="financingResult2"></p>


<footer>

    <div class ="footer-top">
         <div class = "footer-logo-dsc">
            <div class = "footer-logo">
                Auto
                <span>24</span>
                </div>

            <div class = "footer-dsc"> Deutschlands praktische Fahrzeugbörse für Neu- und Gebrauchtwagen. Unkompliziert, sicher und schnell.</div>
            </div>


            <div>
                <div class = "footer-heading">Fahrzeuge</div>
                <ul class= "footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="#">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class = "footer-heading">Kundenservice</div>
                <ul class ="footer-links">
                    <li><a href="#">Fahrzeug verkaufen</a></li>
                    <li><a href="#">Hilfe & FAQ</a></li>
                    <li><a href="#">Finanzierung</a></li>
                    <li><a href="#">Versicherung</a></li>
                </ul>
                </div>
            <div>
                <div class = "footer-heading">Unternehmen</div>
                <ul class = "footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href = "#"> Datenschutz </a></li>
                    <li><a href = "#"> AGB </a></li>
                    <li> <a href = "#"> Partner </a></li>
                </ul>
            </div>
            </div>
         </div>

        </div>
    </footer>
    <script src="validation.js"></script>
</body>
