<?php
session_start();
?>

<?php
require_once "db.php";
$db        = getDB();
$result    = mysqli_query($db,
    "SELECT * FROM cars
     WHERE iid NOT IN (
         SELECT car_id FROM bookings
         WHERE status IN ('in_bearbeitung', 'versandt', 'fertig')
         AND car_id IS NOT NULL
     )
     ORDER BY id ASC"
);
$fahrzeuge = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset = "UTF-8">
    <title>Fahrzeug Liste</title>
    <link rel="stylesheet" href="mystyle.css">
</head>

<!-- Lukas: Navigation -->
<body>
    <?php $showFav = true; require_once 'nav.php'; ?>

    <!-- Toggle Button + Live-Suche -->
    <div class="search-config-bar">
        <div class="search-col">
            <button class="search-toggle-btn" id="searchToggleBtn" onclick="toggleSearchConfig()" title="Suchkonfigurator öffnen/schließen">
                <svg class="search-toggle-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 14h1v-3l2-4h8l2 4v3h1"/>
                    <path d="M3 14v2h15v-2"/>
                    <circle cx="6.5" cy="16" r="1.8"/>
                    <circle cx="14.5" cy="16" r="1.8"/>
                    <circle cx="20" cy="6.5" r="3"/>
                    <line x1="22.2" y1="8.7" x2="24" y2="10.5"/>
                </svg>
                <span class="search-toggle-label">Suche</span>
                <svg class="search-toggle-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
        </div>
        <div class="search-col search-col--mid">
            <input type="text" id="carSearchInput" class="search-bar-inline" placeholder="Marke oder Modell suchen..." oninput="searchCars()">
        </div>
        <div class="search-col"></div>
    </div>

    <!-- Aufklappbarer Suchkonfigurator -->
    <div class="search-configurator" id="searchConfigurator">

<!-- Niclas: Budget-Filter mit Schieberegler -->
<div class ="filter-section">
<div class="budget-filter">
    <h5>Maximales Budget</h5>

    <div class="slider-container">

        <input
            type="range"
            id="budgetInput"
            min="30000"
            max="150000"
            step="1000"
            value="150000"
            oninput="updateBudgetLabel()">

        <div id="budgetValue">
            150.000 €
        </div>
    </div>
</div>
<!-- Niclas: Sortier-Buttons -->
<div class="sort-filter">
    <h5>Fahrzeuge sortieren</h5>

    <div class="filter-button-row">
        <button class="filter-btn" onclick="sortCarsByPrice(true)">
            Preis ↑
        </button>

        <button class="filter-btn" onclick="sortCarsByPrice(false)">
            Preis ↓
        </button>
    </div>
</div>

<!-- Niclas: Filter-Buttons für Baujahr -->
<div class="year-filter">
    <h5>Baujahr ab</h5>

    <div class="slider-container">

        <input
            type="range"
            id="yearInput"
            min="1980"
            max="2026"
            step="1"
            value="1980"
            oninput="updateYearLabel()">

        <div id="yearValue">
            1980
        </div>
    </div>
</div>
</div>
</div>
<!--Niclas: Filter-Buttons Antrieb-->
<div class="filter-section">
    <h5>Antriebsart</h5>
    <div class="filter-button-row">
        <button class="filter-btn" onclick="toggleDriveFilter('Frontantrieb')">
            Frontantrieb
        </button>

        <button class="filter-btn" onclick="toggleDriveFilter('Allrad')">
            Allrad
        </button>

        <button class="filter-btn" onclick="toggleDriveFilter('Heckantrieb')">
            Heckantrieb
        </button>
    </div>
    </div>

<!-- Niclas: Filter-Buttons Leistung -->
<div class="filter-section">
    <h5>Leistung ab (PS)</h5>
    <div class="slider-container">

        <input
            type="range"
            id="powerInput"
            min="50"
            max="1000"
            step="1"
            value="1000"
            oninput="updatePowerLabel()">

        <div id="powerValue">
            1000 PS
        </div>
    </div>
    </div>

    <!--Niclas: Filter-Buttons Zustand-->
<div class="filter-section">
    <h5>Zustand</h5>
    <div class="filter-button-row">
        <button class="filter-btn" onclick="toggleDriveFilter('Gebrauchtwagen')">
            Gebrauchtwagen
        </button>

        <button class="filter-btn" onclick="toggleDriveFilter('Neuwagen')">
            Neuwagen
        </button>
    </div>
    </div>

<!-- Niclas: Filter-Buttons Kilometerstand -->
<div class="filter-section">
    <h5>Kilometerstand bis</h5>
    <div class="slider-container">

        <input
            type="range"
            id="kmInput"
            min="0"
            max="600000"
            step="100"
            value="600000"
            oninput="updateKmLabel()">

        <div id="kmValue">
            600.000 km
        </div>
    </div>
    </div>

    </div><!-- Ende search-configurator -->

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
            data-price="<?php echo $auto['preis']; ?>"
            data-kategorie="<?php echo $auto['kategorie']; ?>">

            <div class="car-image">
                <span class="car-badge-used"><?php echo strtolower($auto['kategorie']) === 'neuwagen' ? 'Neuwagen' : 'Gebrauchtwagen'; ?></span>
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


<?php require_once 'footer.php'; ?>
    <script src="js/favLogik.js"></script>
    <script>
        function toggleSearchConfig() {
            const config = document.getElementById('searchConfigurator');
            const btn = document.getElementById('searchToggleBtn');
            config.classList.toggle('open');
            btn.classList.toggle('active');
        }
    </script>
</body>
