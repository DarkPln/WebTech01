// ── FAVORITEN LOGIK ──
const favorites = new Set();

// ── PANEL ÖFFNEN / SCHLIESSEN ──
function togglePanel() {
    const panel   = document.getElementById('favList');
    const overlay = document.getElementById('favOvl');
    if (!panel) return;
    const isOpen  = panel.classList.contains('active');
    panel.classList.toggle('active', !isOpen);
    overlay.classList.toggle('active', !isOpen);
    document.body.style.overflow = isOpen ? '' : 'hidden';
}

// ── AJAX AN PHP SCHICKEN ──
async function sendFavAction(action, carId) {
    const body = carId !== undefined ? { action, carId } : { action };
    await fetch('toggle_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
    });
}

// ── UI AKTUALISIEREN ──
function updateUI() {
    const counter     = favorites.size;
    const countEl     = document.getElementById('favCount');
    const navBtn      = document.getElementById('nFavBtn');
    const listCountEl = document.getElementById('favListCount');
    const footer      = document.getElementById('favListFooter');
    const emptyEl     = document.getElementById('favListEmpty');

    if (!countEl) return;

    countEl.textContent = counter;
    countEl.style.display = counter > 0 ? 'inline-block' : 'none';
    if (navBtn) navBtn.classList.toggle('has-items', counter > 0);

    if (listCountEl) {
        listCountEl.textContent =
            counter === 0 ? '0 Fahrzeuge in den Favoriten' :
            counter === 1 ? '1 Fahrzeug in den Favoriten' :
            `${counter} Fahrzeuge in den Favoriten`;
    }

    if (emptyEl) emptyEl.style.display  = counter === 0 ? 'flex' : 'none';
    if (footer)  footer.style.display   = counter > 0   ? 'block' : 'none';

    if (counter > 0) {
        let total = 0;
        favorites.forEach(id => {
            const card = document.querySelector(`.car-card[data-id="${id}"]`);
            if (card) {
                total += parseInt((card.dataset.price || '0').replace(/[^0-9]/g, ''), 10) || 0;
            }
        });
        const totalEl = document.getElementById('totalCostValue');
        if (totalEl) totalEl.textContent = total.toLocaleString('de-DE') + ' €';
    }

    renderList();
}

// ── PANEL-LISTE RENDERN ──
function renderList() {
    const list = document.getElementById('favItems');
    if (!list) return;
    list.innerHTML = '';

    favorites.forEach(id => {
        const card = document.querySelector(`.car-card[data-id="${id}"]`);
        if (!card) return;

        const item = document.createElement('div');
        item.className = 'fav-item';
        item.dataset.id = id;

        const img    = card.querySelector('.car-img');
        const imgSrc = img ? img.src : '';
        const make   = card.dataset.make  || '';
        const model  = card.dataset.model || '';
        const year   = card.dataset.year  || '';
        const fuel   = card.dataset.fuel  || '';
        const km     = card.dataset.km    || '';
        const price  = card.dataset.price || '';

        item.innerHTML = `
            <div class="fav-item-thumbnail">
                <img src="${imgSrc}" alt="${make} ${model}" width="80" height="56" style="object-fit:cover; border-radius:3px;">
            </div>
            <div class="fav-item-info">
                <div class="fav-item-make">${make}</div>
                <div class="fav-item-model">${model}</div>
                <div class="fav-item-meta">${year} · ${fuel} · ${km}</div>
                <div class="fav-item-price">${Number(price).toLocaleString('de-DE')} €</div>
            </div>
            <button class="fav-item-remove-btn" onclick="removeFav(${id})" title="Entfernen">&#x2715;</button>
        `;
        list.appendChild(item);
    });
}

// ── HERZ TOGGLEN ──
async function toggleFavorite(btn) {
    const card = btn.closest('.car-card');
    if (!card) return;

    const id = parseInt(card.dataset.id, 10);

    if (favorites.has(id)) {
        favorites.delete(id);
        btn.innerHTML         = '&#9825;';
        btn.style.color       = '';
        btn.style.borderColor = '';
    } else {
        favorites.add(id);
        btn.innerHTML         = '&#9829;';
        btn.style.color       = 'red';
        btn.style.borderColor = 'red';
    }

    updateUI();
    await sendFavAction('toggle', id);
}

// ── EINZELN ENTFERNEN ──
async function removeFav(id) {
    id = parseInt(id, 10);
    if (!favorites.has(id)) return;
    favorites.delete(id);

    const card = document.querySelector(`.car-card[data-id="${id}"]`);
    if (card) {
        const btn = card.querySelector('.car-fav');
        if (btn) { btn.innerHTML = '&#9825;'; btn.style.color = ''; }
    }

    updateUI();
    await sendFavAction('toggle', id);
}

// ── ALLE ENTFERNEN ──
async function clearAllFavs() {
    favorites.forEach(id => {
        const card = document.querySelector(`.car-card[data-id="${id}"]`);
        if (card) {
            const btn = card.querySelector('.car-fav');
            if (btn) { btn.innerHTML = '&#9825;'; btn.style.color = ''; }
        }
    });
    favorites.clear();
    updateUI();
    await sendFavAction('clear');
}

// ── MERKLISTE: CHECKBOX-BUCHUNG ──
function initMerklisteBuchung() {
    const checkboxes = document.querySelectorAll('.merkliste-checkbox');
    const buchungBtn = document.getElementById('buchungAusgewaehlt');
    const countSpan  = document.getElementById('ausgewaehltCount');

    if (!checkboxes.length || !buchungBtn) return;

    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.merkliste-checkbox:checked').length;
            if (countSpan) countSpan.textContent = checked;
            buchungBtn.disabled = checked === 0;
        });
    });

    buchungBtn.addEventListener('click', function() {
        const loggedIn = (typeof authState !== 'undefined') && authState.loggedIn;
        if (!loggedIn) {
            alert('Bitte einloggen um zu buchen.');
            window.location.href = 'login.php';
            return;
        }

        const ausgewaehlt = document.querySelectorAll('.merkliste-checkbox:checked');
        if (!ausgewaehlt.length) return;

        const namen = [];
        ausgewaehlt.forEach(function(cb) { namen.push(cb.dataset.carName); });

        if (!confirm('Möchten Sie folgende Fahrzeuge buchen?\n\n' + namen.join('\n'))) return;

        ausgewaehlt.forEach(function(cb) {
            createBooking(cb.dataset.carId, cb.dataset.carName, cb.dataset.carPrice);
        });

        window.location.href = 'buchungen.php';
    });
}

// ── EINZIGER DOMContentLoaded BLOCK ──
document.addEventListener('DOMContentLoaded', async function () {

    // Session-Daten holen
    try {
        const res  = await fetch('get_favorites.php');
        const data = await res.json();

        if (data.favorites && data.favorites.length > 0) {
            data.favorites.forEach(id => {
                favorites.add(parseInt(id, 10));

                const card = document.querySelector(`.car-card[data-id="${id}"]`);
                if (card) {
                    const btn = card.querySelector('.car-fav');
                    if (btn) {
                        btn.innerHTML         = '&#9829;';
                        btn.style.color       = 'red';
                        btn.style.borderColor = 'red';
                    }
                }
            });
            updateUI();
        }
    } catch (e) {
        console.error('Fehler beim Laden der Merkliste:', e);
    }

    // Herz-Buttons verdrahten
    document.querySelectorAll('.car-fav').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleFavorite(this);
        });
    });

    // Clear-Button verdrahten
    const clearBtn = document.getElementById('clearFavListBtn');
    if (clearBtn) clearBtn.addEventListener('click', clearAllFavs);

    // Merkliste Checkbox-Buchung initialisieren
    initMerklisteBuchung();
});


