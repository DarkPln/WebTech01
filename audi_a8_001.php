<!-- Lukas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title> Audi A8</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $carTitle   = "Audi A8 (Limousine)";
        $carDesc1   = "Der Audi A8 ist eine luxuriöse Limousine mit hohem Fahrkomfort und modernster Ausstattung. Das Fahrzeug überzeugt durch ein astreines Interieur, hochwertige Verarbeitung und elegantes Design.";
        $carDesc2   = "Der Innenraum bietet sehr viel Platz und Komfortausstattung wie Alcantara Sitze, Navigationssystem und Klimaautomatik. Dank ausgeprägter Fahrassistenzsysteme eignet er sich perfekt für Langstreckenreisen.";
    ?>

    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="about.php">Auto verkaufen</a></li>
        </ul>

        <!-- Lukas: Fav -->
         <button class = "nFav-btn" id="nFavBtn" onclick= "togglePanel()">
            <span class = "navFav-label"> ♡ Merkliste </span>
            <span class = "navFav-count" id="favCount" style = "display: none">0</span>
         </button>
    </nav>

    <div class = "fav-ovl" id="favOvl" onclick = "togglePanel()"></div>

    <!-- Lukas: Favoritenliste / warenkorb -->
    <div class = "fav-list" id="favList" >
        <div class = "fav-list-header">
            <div class = "fav-list-title">Merkliste </div>
            <div class = "fav-list-dsc" id="favListCount">0 Fahrzeuge in den Favoriten</div>
            <button class = "fav-list-close-btn" onclick= "togglePanel()">X</button>
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

    <br> <br> <br>

    <h1><?php echo $carTitle; ?></h1>

    <div class="car-card" data-id="audi_a8_001" data-make="Audi" data-model="A8L" data-year="2024" data-fuel="Benzin" data-body="Limousine" data-power="510 PS" data-km="40000 km" data-drive="S-Line Quattro" data-price="89900">
        <div class="car-imageA8">
            <span class="car-badge-used">Gebraucht</span>
            <!-- Klick auf das Herz wird in validation.js behandelt: Event-Listener (.car-fav) ruft `toggleFavorite(btn)` auf -->
            <button class="car-fav" type="button" >♡</button>
            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg/1920px-Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg" alt="Audi A8" height = 200 width = 400
                alt="Audi A8L"
                class="car-imgA8"
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
            </div>
        </div>
    </div>

    <p><?php echo $carDesc1; ?></p>
    <p><?php echo $carDesc2; ?></p>

    <button class="fav-list-btn" type="button"
        data-id="audi_a8_001"
        data-make="Audi"
        data-model="A8"
        data-year="2021"
        data-fuel="Diesel"
        data-km="33.000 km"
        data-price="48.750 €"
        data-image="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg/1920px-Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg">
        Zu Favoriten hinzufügen
    </button>

     <footer>

        <div class ="footer-top">
            <div class = footer-logo-dsc>
            <div class = "footer-logo">
                Auto
                <span>24</span>
                </div>

            <div class = "footer-dsc"> Deutschlands praktische Fahrzeugbörse für Neu- und Gebrauchtwagen. Unkompliziert, sicher und schnell.</div>
            </div>


            <div>
                <div class = "footer-heading">Fahrzeuge</div>
                <ul class "footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="#">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class = "footer-heading">Kundenservice</div>
                <ul class "footer-links">
                    <li><a href="#">Fahrzeug verkaufen</a></li>
                    <li><a href="#">Hilfe & FAQ</a></li>
                    <li><a href="#">Finanzierung</a></li>
                    <li><a href="#">Versicherung</a></li>
                </ul>
                </div>
            <div>
                <div class = "footer-heading">Unternehmen</div>
                <ul class "footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href = "#"> Datenschutz </a></li>
                    <li><a href = "#"> AGB </a></li>
                    <li> <a href = "#"> Partner </a></li>
                </ul>
            </div>
            </div>
         </div>


        <div class="footer-bot">
        <span> &copy 2026 Auto24. Alle Rechte vorbehalten.</span>
        <div class="footer-bot-links">
            <a href="index.php">Home</a>
            <a href="gebrauchtwagenList.php">Gebrauchtwagen</a>
            <a href="about.php">Impressum</a>
        </div>


        </div>
    </footer>

<script src="validation.js"></script>
</body>
</html>
<!-- Lukas -->
