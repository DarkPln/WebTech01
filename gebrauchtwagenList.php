<?php
session_start();
?>

<?php
require_once "db.php";
$result    = getDB()->query("SELECT * FROM cars ORDER BY id ASC");
$fahrzeuge = $result->fetch_all(MYSQLI_ASSOC);
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

    <br>
    <br>
    <br>

<!-- Niclas: Budget-Filter mit Schieberegler -->
<div class ="filter-section">
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
        <div class="filter-button-row">
            <button class="filter-btn" onclick="filterByBudget()">
             Anwenden
             </button>

             <button class="filter-btn" onclick="resetBudgetFilter()">
             Reset
            </button>
            </div>   
        </div>     
</div>
<!-- Niclas: Sortier-Buttons -->
<div class="sort-filter">
    <h3>Fahrzeuge sortieren</h3>

    <div class="filter-button-row">
        <button class="filter-btn" onclick="sortCarsByPrice(true)">
            Preis ↑
        </button>

        <button class="filter-btn" onclick="sortCarsByPrice(false)">
            Preis ↓
        </button>
    </div>
</div>

<!-- Niclas: Suchfunktion nach Marke -->
<div class="search-filter">
    <h3>Fahrzeug suchen</h3>

    <input
        type="text"
        id="carSearchInput"
        placeholder="z.B. Audi oder C 220">

    <div class="filter-button-row">
        <button class="filter-btn" onclick="searchCars()">
            Suchen
        </button>

        <button class="filter-btn" onclick="resetCarSearch()">
            Reset
        </button>
    </div>
</div>
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
                <span class="car-badge-used"><?php echo strtolower($auto['kategorie']) === 'neuwagen' ? 'Neuwagen' : 'Gebraucht'; ?></span>
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
    <h2>Kauffinanzierung</h2>
    <p>Geben Sie den gewünschten Finanzierungsbetrag ein, um die monatlichen Raten zu berechnen (5% Zins).</p>
    <a href="finanzierung.php" class="home-btn-primary" style="display:inline-block; margin: 16px auto;">Zum Finanzierungsrechner</a>
</section>


<?php require_once 'footer.php'; ?>
    <script src="validation.js"></script>
    <script src="favLogik.js"></script>
</body>
