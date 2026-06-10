// Favoriten Logik Lukas 
const favorites   = new Set();
const favCarData  = {};          // id → { make, model, year, fuel, km, price, imgSrc }

// panel öffnen/schliessen 
async function togglePanel() {
    const panel   = document.getElementById('favList');
    const overlay = document.getElementById('favOvl');
    if (!panel) return;
    const isOpen  = panel.classList.contains('active');
    panel.classList.toggle('active', !isOpen);
    overlay.classList.toggle('active', !isOpen);
    document.body.style.overflow = isOpen ? '' : 'hidden';

    if (!isOpen) {
        // panel öffnet: Autodaten von Server holen und Panel  rendern
        try {
            const res  = await fetch('get_favorites.php');
            const data = await res.json();
            if (Array.isArray(data.cars)) {
                data.cars.forEach(car => {
                    favCarData[parseInt(car.iid, 10)] = car;
                });
            }
        } catch (e) {}
        renderList();
    }
}

// Notification 
let notTimer = null;

function showNotification(msg) {
    let el = document.querySelector('.fav-notification');
    if (!el) {
        el = document.createElement('div');
        el.className = 'fav-notification';
        document.body.appendChild(el);
    }
    el.innerHTML = msg;
    el.classList.add('show');
    clearTimeout(notTimer);
    notTimer = setTimeout(() => el.classList.remove('show'), 3000);
}

// wird dann in ToggleFavorite und removefav aufgerufen um serverseitig die session/db zu aktualisieren
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
            const d = getCarData(id);
            if (d) total += parseInt((d.price || '0').toString().replace(/[^0-9]/g, ''), 10) || 0;
        });
        const totalEl = document.getElementById('totalCostValue');
        if (totalEl) totalEl.textContent = total.toLocaleString('de-DE') + ' €';
    }

    renderList();
}

// ── AUTO-DATEN HOLEN (DOM-Card oder gespeicherte Server-Daten) ──
function getCarData(id) {
    const card = document.querySelector(`.car-card[data-id="${id}"]`);
    if (card) {
        const img = card.querySelector('.car-img');
        return {
            imgSrc: img ? img.src : '',
            make:   card.dataset.make  || '',
            model:  card.dataset.model || '',
            year:   card.dataset.year  || '',
            fuel:   card.dataset.fuel  || '',
            km:     card.dataset.km    || '',
            price:  card.dataset.price || '',
        };
    }
    const d = favCarData[id];
    if (!d) return null;
    return {
        imgSrc: d.imagepath || '',
        make:   d.marke     || '',
        model:  d.modell    || '',
        year:   d.baujahr   || '',
        fuel:   d.kraftstoff || '',
        km:     Number(d.kilometerstand || 0).toLocaleString('de-DE') + ' km',
        price:  d.preis     || '',
    };
}

// ── PANEL-LISTE RENDERN ──
function renderList() {
    const list = document.getElementById('favItems');
    if (!list) return;
    list.innerHTML = '';

    favorites.forEach(id => {
        const d = getCarData(id);
        if (!d) return;

        const item = document.createElement('div');
        item.className  = 'fav-item';
        item.dataset.id = id;

        item.innerHTML = `
            <div class="fav-item-thumbnail">
                <img src="${d.imgSrc}" alt="${d.make} ${d.model}" width="80" height="56" style="object-fit:cover; border-radius:3px;">
            </div>
            <div class="fav-item-info">
                <div class="fav-item-make">${d.make}</div>
                <div class="fav-item-model">${d.model}</div>
                <div class="fav-item-meta">${d.year} · ${d.fuel} · ${d.km}</div>
                <div class="fav-item-price">${Number(d.price).toLocaleString('de-DE')} €</div>
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
        showNotification(`<strong>${card.dataset.make} ${card.dataset.model}</strong> aus Merkliste entfernt`);
    } else {
        favorites.add(id);
        btn.innerHTML         = '&#9829;';
        btn.style.color       = 'red';
        btn.style.borderColor = 'red';
        showNotification(`<strong>${card.dataset.make} ${card.dataset.model}</strong> zur Merkliste hinzugefügt`);
        // Daten cachen falls noch nicht vorhanden (z.B. auf item.php)
        if (!favCarData[id]) {
            const img = card.querySelector('.car-img');
            favCarData[id] = {
                imagepath:      img ? img.getAttribute('src') : '',
                marke:          card.dataset.make   || '',
                modell:         card.dataset.model  || '',
                baujahr:        card.dataset.year   || '',
                kraftstoff:     card.dataset.fuel   || '',
                kilometerstand: (card.dataset.km || '').replace(/[^0-9]/g, ''),
                preis:          card.dataset.price  || '',
            };
        }
    }

    updateUI();
    await sendFavAction('toggle', id);
}

// ── EINZELN ENTFERNEN ──
async function removeFav(id) {
    id = parseInt(id, 10);
    if (!favorites.has(id)) return;

    const d = getCarData(id);
    favorites.delete(id);

    const card = document.querySelector(`.car-card[data-id="${id}"]`);
    if (card) {
        const btn = card.querySelector('.car-fav');
        if (btn) { btn.innerHTML = '&#9825;'; btn.style.color = ''; }
    }

    if (d) showNotification(`<strong>${d.make} ${d.model}</strong> aus Merkliste entfernt`);
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

        if (Array.isArray(data.cars)) {
            data.cars.forEach(car => {
                favCarData[parseInt(car.iid, 10)] = car;
            });
        }

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


