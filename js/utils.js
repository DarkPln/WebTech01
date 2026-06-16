// ===== UTILITIES & UI-HILFEN =====
// Niclas

/* Light Mode Toggle: Niclas */
function toggleMode() {
    const isLight = document.body.classList.toggle("light-mode");
    localStorage.setItem('auto24_lightMode', isLight ? '1' : '0');
    // Modus-Präferenz auch als Cookie speichern (falls Cookies akzeptiert)
    if (typeof setCookie === 'function' && getCookie('auto24_consent') === 'accepted') {
        setCookie('auto24_lightMode', isLight ? '1' : '0', 365);
    }
    document.querySelectorAll('.mode-btn').forEach(btn => {
        btn.textContent = isLight ? 'Dark' : 'Light';
    });
}

(function applyStoredMode() {
    // PHP setzt light-mode bereits serverseitig via $_COOKIE (nav.php) — kein Flash.
    // Dieser Fallback greift nur wenn kein Cookie vorhanden ist (z.B. Cookies abgelehnt),
    // dann liest localStorage den zuletzt gespeicherten Wert.
    if (!document.body.classList.contains('light-mode') &&
        localStorage.getItem('auto24_lightMode') === '1') {
        document.body.classList.add('light-mode');
    }
    document.addEventListener('DOMContentLoaded', function() {
        const isLight = document.body.classList.contains('light-mode');
        document.querySelectorAll('.mode-btn').forEach(btn => {
            btn.textContent = isLight ? 'Dark' : 'Light';
        });
    });
})();

/* Preisberechnung mit Steuern */
function calculatePrice() {
    const input = document.getElementById("priceInput");
    const value = Number(input.value);
    if (value <= 0) { alert("Bitte gültigen Preis eingeben"); return; }
    const total = value * 1.19;
    document.getElementById("priceWithoutTax").textContent = "Preis ohne Steuer: " + value.toFixed(2) + " €";
    document.getElementById("priceWithTax").textContent    = "Preis mit 19% Steuer: " + total.toFixed(2) + " €";
}

/* Finanzierungshilfe */
function calculateFinancing() {
    const value    = Number(document.getElementById("financingInput").value);
    const loanTerm = Number(document.getElementById("loanTermInput").value);
    if (value <= 0)              { alert("Bitte gültigen Finanzierungsbetrag eingeben"); return; }
    if (loanTerm < 12 || loanTerm > 48) { alert("Bitte gültige Laufzeit eingeben (12-48 Monate)"); return; }
    const total   = value * 1.05;
    document.getElementById("financingResult").textContent  = "Monatliche Rate: " + (total / loanTerm).toFixed(2) + " €";
    document.getElementById("financingResult2").textContent = "Gesamtbetrag: " + total.toFixed(2) + " €";
}

/* Finanzierungsrechner auf der Detailseite */
function initItemFinancing() {
    var toggle = document.getElementById('financingToggle');
    var body   = document.getElementById('financingBody');
    if (!toggle || !body) return;
    toggle.addEventListener('click', function() {
        var open = body.classList.toggle('open');
        toggle.classList.toggle('open', open);
    });
}

function calculateItemFinancing() {
    //Variablen Wert, Laufzeit und Ergebnis-Element holen:
    var value    = Number(document.getElementById('itemFinancingAmount').value);
    var loanTerm = Number(document.getElementById('itemLoanTerm').value);
    var result   = document.getElementById('itemFinancingResult');
    //Behandlung von ungültigen Eingaben:
    if (!result) return;
    if (value <= 0)              { result.textContent = 'Bitte gültigen Betrag eingeben.'; return; }
    if (loanTerm < 12 || loanTerm > 48) { result.textContent = 'Laufzeit muss zwischen 12 und 48 Monaten liegen.'; return; }
    //Variablen berechnen und Ergebnis anzeigen:
    var total   = value * 1.05;
    var monthly = (total / loanTerm).toFixed(2).replace('.', ',');
    var gesamt  = total.toFixed(2).replace('.', ',');
    //Darstellung mit Monatsrate und Gesamtbetrag:
    result.innerHTML =
        '<strong>' + monthly + ' €</strong> / Monat' +
        '<br><span class="financing-sub">Gesamtbetrag: ' + gesamt + ' € &nbsp;·&nbsp; inkl. 5 % Finanzierungskosten</span>';
}

/* Passwort Generator: Niclas */
function generatePassword() {
    //Variablen für Eingabefeld, Zeichensatz und generiertes Passwort:
    const input   = document.getElementById("pwInput");
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
    //leere Zeichenketten-Variable für das Passwort erstellen:
    let password  = "";
    //Algorithmus; Zufallsgenerator:
    for (let i = 0; i < 12; i++) {
        password += charset[Math.floor(Math.random() * charset.length)];
    }
    //Generiertes Passwort in Eingabefeld und darunter anzeigen:
    input.value = password;
    const output = document.getElementById('generatedPassword');
    if (output) output.textContent = "Generiertes Passwort: " + password;
}

/* GebrauchtwagenList: Niclas */
function applyUrlFilter() {
    const filter = new URLSearchParams(window.location.search).get('filter');
    if (!filter) return;

    document.querySelectorAll('.car-card').forEach(card => {
        let visible;
        switch (filter) {
            case 'neuwagen':       visible = card.dataset.kategorie === 'neuwagen'; break;
            case 'gebrauchtwagen': visible = card.dataset.kategorie === 'gebrauchtwagen'; break;
            case 'elektro':        visible = card.dataset.fuel === 'Elektro'; break;
            case 'sonderangebot':  visible = Number(card.dataset.price) <= 60000; break;
            default:               visible = true;
        }
        card.style.display = visible ? '' : 'none';
    });
}

function updateBudgetLabel() {
    const value = Number(document.getElementById("budgetInput").value);
    document.getElementById("budgetValue").textContent = value.toLocaleString("de-DE") + " €";
}

//Sortierfunktion für Preis (aufsteigend/absteigend):
function sortCarsByPrice(asc) {
    const container = document.getElementById("carLayout");
    //Sortiert Arrayelemente nach Werten:
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => asc
            ? Number(a.dataset.price) - Number(b.dataset.price)
            : Number(b.dataset.price) - Number(a.dataset.price))
        .forEach(car => container.appendChild(car));
}

//Sortierfunktion für Baujahr (aufsteigend/absteigend):
function sortCarsByYear(asc) {
    const container = document.getElementById("carLayout");
    //Sortiert Arrayelemente nach Werten:
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => asc
            ? Number(a.dataset.year) - Number(b.dataset.year)
            : Number(b.dataset.year) - Number(a.dataset.year))
        .forEach(car => container.appendChild(car));
}

//Anwendung aller Filter:
function searchCars() {
    applyAllFilters();
}

//Bei Änderung der Filter-Inputs, Anzeige aktualisieren:
function updateYearLabel() {
    const value = Number(document.getElementById("yearInput").value);
    document.getElementById("yearValue").textContent = value;
}

function updatePowerLabel() {
    const value = Number(document.getElementById("powerInput").value);
    document.getElementById("powerValue").textContent = value + " PS";
}

function updateKmLabel() {
    const value = Number(document.getElementById("kmInput").value);
    document.getElementById("kmValue").textContent = value.toLocaleString("de-DE") + " km";
}

const activeFilters = { drive: new Set(), condition: new Set(), fuel: new Set() };

const driveNormMap = {
    // Allrad
    'allrad': 'Allrad', 'awd': 'Allrad', '4wd': 'Allrad', '4x4': 'Allrad',
    'allradantrieb': 'Allrad', 'permanentallrad': 'Allrad',
    'quattro': 'Allrad',           // Audi
    'xdrive': 'Allrad',            // BMW
    'x drive': 'Allrad',           // BMW
    '4matic': 'Allrad',            // Mercedes
    '4motion': 'Allrad',           // VW
    'syncro': 'Allrad',            // VW (ältere Modelle)
    '4drive': 'Allrad',            // Škoda
    'awd system': 'Allrad',
    'e-four': 'Allrad',            // Toyota Hybrid AWD
    'symmetrical awd': 'Allrad',   // Subaru
    'torsen': 'Allrad',            // Audi
    'superselect': 'Allrad',       // Mitsubishi
    'terrain control': 'Allrad',   // Land Rover
    'grip control': 'Allrad',      // Peugeot
    'intelligrip': 'Allrad',       // Opel/Vauxhall
    'haldex': 'Allrad',
    // Frontantrieb
    'frontantrieb': 'Frontantrieb', 'fwd': 'Frontantrieb',
    'vorderradantrieb': 'Frontantrieb', 'front': 'Frontantrieb',
    // Heckantrieb
    'heckantrieb': 'Heckantrieb', 'rwd': 'Heckantrieb',
    'hinterradantrieb': 'Heckantrieb', 'heck': 'Heckantrieb',
    'propulsion': 'Heckantrieb',   // BMW (älteres Marketing)
};

function normalizeDrive(raw) {
    return driveNormMap[raw.toLowerCase().trim()] || raw;
}

const filterMap = {
    Frontantrieb: 'drive', Allrad: 'drive', Heckantrieb: 'drive',
    Gebrauchtwagen: 'condition', Neuwagen: 'condition',
    Benzin: 'fuel', Diesel: 'fuel', Elektro: 'fuel', Hybrid: 'fuel', Wasserstoff: 'fuel'
};

function toggleDriveFilter(value) {
    const group = filterMap[value];
    if (!group) return;
    const set = activeFilters[group];
    if (set.has(value)) {
        set.delete(value);
    } else {
        set.add(value);
    }
    document.querySelectorAll(".filter-btn").forEach(btn => {
        if (btn.textContent.trim() === value) btn.classList.toggle("active", set.has(value));
    });
}

function applyAllFilters() {
    const budget     = Number(document.getElementById("budgetInput").value);
    const year       = Number(document.getElementById("yearInput").value);
    const power      = Number(document.getElementById("powerInput").value);
    const km         = Number(document.getElementById("kmInput").value);
    const searchTerm = document.getElementById("carSearchInput").value.toLowerCase();

    document.querySelectorAll(".car-card").forEach(car => {
        const carCondition = car.dataset.kategorie === 'neuwagen' ? 'Neuwagen' : 'Gebrauchtwagen';

        const ok =
            Number(car.dataset.price) <= budget &&
            Number(car.dataset.year)  >= year &&
            Number(car.dataset.power) >= power &&
            Number(car.dataset.km)    <= km &&
            (searchTerm === "" || car.dataset.make.toLowerCase().includes(searchTerm) || car.dataset.model.toLowerCase().includes(searchTerm)) &&
            (activeFilters.drive.size     === 0 || activeFilters.drive.has(normalizeDrive(car.dataset.drive))) &&
            (activeFilters.fuel.size      === 0 || activeFilters.fuel.has(car.dataset.fuel)) &&
            (activeFilters.condition.size === 0 || activeFilters.condition.has(carCondition));

        car.style.display = ok ? "" : "none";
    });

    const anyVisible = document.querySelector(".car-card:not([style*='none'])");
    const msg = document.getElementById("noResultsMsg");
    if (msg) msg.style.display = anyVisible ? "none" : "block";
}

function resetAllFilters() {
    document.getElementById("budgetInput").value = 150000;  updateBudgetLabel();
    document.getElementById("yearInput").value   = 1980;    updateYearLabel();
    document.getElementById("powerInput").value  = 50;      updatePowerLabel();
    document.getElementById("kmInput").value     = 600000;  updateKmLabel();
    document.getElementById("carSearchInput").value = "";
    activeFilters.drive.clear();
    activeFilters.condition.clear();
    activeFilters.fuel.clear();
    document.querySelectorAll(".filter-btn.active").forEach(btn => btn.classList.remove("active"));
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
}
