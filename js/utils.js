// ===== UTILITIES & UI-HILFEN =====
// Niclas + tim

/* Light Mode Toggle: Niclas */
function toggleMode() {
    const isLight = document.body.classList.toggle("light-mode");
    localStorage.setItem('auto24_lightMode', isLight ? '1' : '0');
    document.querySelectorAll('.mode-btn').forEach(btn => {
        btn.textContent = isLight ? 'Dark' : 'Light';
    });
}

(function applyStoredMode() {
    if (localStorage.getItem('auto24_lightMode') === '1') {
        document.body.classList.add('light-mode');
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mode-btn').forEach(btn => { btn.textContent = 'Dark'; });
        });
    }
})();

/* Layout-Umschaltung: Niclas */
function setVerticalLayout() {
    const layout = document.getElementById("carLayout");
    if (!layout) return;
    layout.classList.remove("horizontal-layout");
    layout.classList.add("vertical-layout");
}

function setHorizontalLayout() {
    const layout = document.getElementById("carLayout");
    if (!layout) return;
    layout.classList.remove("vertical-layout");
    layout.classList.add("horizontal-layout");
}

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
    var value    = Number(document.getElementById('itemFinancingAmount').value);
    var loanTerm = Number(document.getElementById('itemLoanTerm').value);
    var result   = document.getElementById('itemFinancingResult');
    if (!result) return;
    if (value <= 0)              { result.textContent = 'Bitte gültigen Betrag eingeben.'; return; }
    if (loanTerm < 12 || loanTerm > 48) { result.textContent = 'Laufzeit muss zwischen 12 und 48 Monaten liegen.'; return; }
    var total   = value * 1.05;
    var monthly = (total / loanTerm).toFixed(2).replace('.', ',');
    var gesamt  = total.toFixed(2).replace('.', ',');
    result.innerHTML =
        '<strong>' + monthly + ' €</strong> / Monat' +
        '<br><span class="financing-sub">Gesamtbetrag: ' + gesamt + ' € &nbsp;·&nbsp; inkl. 5 % Finanzierungskosten</span>';
}

/* Passwort Generator: Niclas */
function generatePassword() {
    const input   = document.getElementById("pwInput");
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
    let password  = "";
    for (let i = 0; i < 12; i++) {
        password += charset[Math.floor(Math.random() * charset.length)];
    }
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

function filterByBudget() {
    const budget = Number(document.getElementById("budgetInput").value);
    if (budget <= 0) { alert("Bitte ein gültiges Budget eingeben."); return; }
    document.querySelectorAll(".car-card").forEach(car => {
        car.style.display = Number(car.dataset.price) <= budget ? "" : "none";
    });
}

function resetBudgetFilter() {
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
    document.getElementById("budgetInput").value = 150000;
    updateBudgetLabel();
}

function sortCarsByPrice(asc) {
    const container = document.getElementById("carLayout");
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => asc
            ? Number(a.dataset.price) - Number(b.dataset.price)
            : Number(b.dataset.price) - Number(a.dataset.price))
        .forEach(car => container.appendChild(car));
}

function searchCars() {
    applyAllFilters();
}

function updateYearLabel() {
    const value = Number(document.getElementById("yearInput").value);
    document.getElementById("yearValue").textContent = value;
}

function applyAllFilters() {
    const budget     = Number(document.getElementById("budgetInput").value);
    const year       = Number(document.getElementById("yearInput").value);
    const searchTerm = document.getElementById("carSearchInput").value.toLowerCase();

    document.querySelectorAll(".car-card").forEach(car => {
        const matchesBudget = Number(car.dataset.price) <= budget;
        const matchesYear   = Number(car.dataset.year) >= year;
        const matchesSearch = searchTerm === "" ||
            car.dataset.make.toLowerCase().includes(searchTerm) ||
            car.dataset.model.toLowerCase().includes(searchTerm);
        car.style.display = (matchesBudget && matchesYear && matchesSearch) ? "" : "none";
    });
}

function resetAllFilters() {
    document.getElementById("budgetInput").value = 150000;
    updateBudgetLabel();
    document.getElementById("yearInput").value = 1980;
    updateYearLabel();
    document.getElementById("carSearchInput").value = "";
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
}
