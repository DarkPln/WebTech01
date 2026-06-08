<?php
session_start();
?>

<?php
$data = json_decode(file_get_contents("items.json"), true);
$fahrzeuge = $data["fahrzeuge"];
?>
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
        $financeHeadline = "Kauffinanzierung";
        $financeHint    = "Geben Sie den gewünschten Finanzierungsbetrag ein, um die monatlichen Raten zu berechnen (5% Zins).";
    ?>

    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
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
            <a href="merkliste.php" class="open-full-favs-btn">
            Ganze Merkliste öffnen
            </a>
        </div>
    </div>
    <!-- Lukas: Ende Favs -->

    <br>
    <br>
    <br>

<!-- Niclas: Budget-Filter mit Schieberegler -->
 <div class="budget-filter">
    <h3>Maximales Budget</h3>

    <div class="slider-container">

        <input
            type="range"
            id="budgetInput"
            min="50000"
            max="100000"
            step="1000"
            value="100000"
            oninput="updateBudgetLabel()">

        <div id="budgetValue">
            100.000 €
        </div>

        <button class="filter-btn" onclick="filterByBudget()">
            Anwenden
        </button>

        <button class="filter-btn" onclick="resetBudgetFilter()">
            Reset
        </button>

    </div> 
</div>        

<!-- Niclas: Sortier-Buttons -->
<div class="sort-filter">
    <h3>Fahrzeuge sortieren</h3>

    <button onclick="sortCarsByPriceAsc()">Preis aufsteigend</button>
    <button onclick="sortCarsByPriceDesc()">Preis absteigend</button>
</div>

<!-- Niclas: Suchfunktion nach Marke -->
<div class="search-filter">
    <h3>Fahrzeug suchen</h3>

    <input
        type="text"
        id="carSearchInput"
        placeholder="z.B. Audi oder C 220">

    <button onclick="searchCars()">Suchen</button>
    <button onclick="resetCarSearch()">Suche zurücksetzen</button>
</div>

<div id = "carLayout" class="cars-grid horizontal-layout">

    <?php foreach ($fahrzeuge as $auto): ?>
        <div class="car-card"
            data-id="<?php echo $auto['iid']; ?>"
            data-make="<?php echo $auto['marke']; ?>"
            data-model="<?php echo $auto['modell']; ?>"
            data-year="<?php echo $auto['baujahr']; ?>"
            data-fuel="<?php echo $auto['kraftstoff']; ?>"
            data-km="<?php echo $auto['kilometerstand']; ?> km"
            data-drive="<?php echo $auto['antrieb']; ?>"
            data-price="<?php echo $auto['preis']; ?>">

            <div class="car-image">
                <span class="car-badge-used">Gebraucht</span>
                <button class="car-fav" type="button" data-id="<?= $auto['iid'] ?>">♡</button>

                <img
                    src="<?php echo $auto['imagepath']; ?>"
                    alt="<?php echo $auto['name']; ?>"
                    class="car-img">
            </div>

            <div class="car-body">
                <div class="car-make"><?php echo strtoupper($auto['marke']); ?></div>
                <div class="car-model"><?php echo $auto['modell']; ?></div>
                <div class="car-year">
                    <?php echo $auto['baujahr']; ?> ·
                    <?php echo $auto['kraftstoff']; ?> ·
                    <?php echo ucfirst($auto['unterkategorie']); ?>
                </div>

                <div class="car-specs">
                    <div class="car-spec">
                        <div class="car-spec-val"><?php echo $auto['leistung_ps']; ?> PS</div>
                        <div class="car-spec-key">Leistung</div>
                    </div>

                    <div class="car-spec">
                        <div class="car-spec-val"><?php echo number_format($auto['kilometerstand'], 0, ',', '.'); ?> km</div>
                        <div class="car-spec-key">Kilometerstand</div>
                    </div>

                    <div class="car-spec">
                        <div class="car-spec-val"><?php echo $auto['antrieb']; ?></div>
                        <div class="car-spec-key">Antrieb</div>
                    </div>
                </div>

                <div class="car-footer">
                    <div>
                        <div class="car-price">
                            <?php echo number_format($auto['preis'], 0, ',', '.'); ?> €
                        </div>
                        <div class="car-price-note">inkl. MwSt.</div>
                    </div>

                    <a href="item.php?pid=<?php echo $auto['iid']; ?>">
                        <button class="car-btn">Details</button>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
</div>

<section class="finance-teaser">
    <h2><?php echo $financeHeadline; ?></h2>
    <p><?php echo $financeHint; ?></p>
    <a href="finanzierung.php" class="home-btn-primary" style="display:inline-block; margin: 16px auto;">Zum Finanzierungsrechner</a>
</section>


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
                <div class="footer-heading">Fahrzeuge</div>
                <ul class="footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-heading">Kundenservice</div>
                <ul class="footer-links">
                    <li><a href="fahrzeug-verkaufen.php">Fahrzeug verkaufen</a></li>
                    <li><a href="faq.php">Hilfe &amp; FAQ</a></li>
                    <li><a href="finanzierung.php">Finanzierung</a></li>
                    <li><a href="versicherung.php">Versicherung</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-heading">Unternehmen</div>
                <ul class="footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href="datenschutz.php">Datenschutz</a></li>
                    <li><a href="agb.php">AGB</a></li>
                    <li><a href="partner.php">Partner</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <script src="validation.js"></script>
    <script src="favLogik.js"></script>
</body>
