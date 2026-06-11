// ===== INSERATE =====
// Autor: tim

function checkVehicleField(f) {
    if (f.value.trim()) f.classList.add('field-ok');
    else                f.classList.remove('field-ok');
}

function initVehicleForm() {
    var form = document.getElementById('vehicleForm');
    if (!form) return;

    // Live-Feedback: Feld grün sobald ausgefüllt
    form.querySelectorAll('[required]').forEach(function(f) {
        f.addEventListener('input',  function() { checkVehicleField(f); });
        f.addEventListener('change', function() { checkVehicleField(f); });
    });

    var uploadArea    = document.getElementById('uploadArea');
    var fileInput     = document.getElementById('sell-images');
    var previewGrid   = document.getElementById('imagePreviewGrid');
    var uploadCount   = document.getElementById('uploadCount');
    var selectedFiles = [];

    function updateCount() {
        if (!uploadCount) return;
        if (selectedFiles.length === 0) {
            uploadCount.style.display = 'none';
        } else {
            uploadCount.style.display = 'block';
            uploadCount.textContent = 'Foto ausgewählt: ' + selectedFiles[0].name;
        }
    }

    function renderPreviews() {
        if (!previewGrid) return;
        previewGrid.innerHTML = '';
        selectedFiles.forEach(function(file, i) {
            var item = document.createElement('div');
            item.className = 'image-preview-item';

            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-img';
            removeBtn.textContent = '×';
            removeBtn.addEventListener('click', function() {
                selectedFiles.splice(i, 1);
                renderPreviews();
                updateCount();
            });

            item.appendChild(img);
            item.appendChild(removeBtn);
            previewGrid.appendChild(item);
        });
        updateCount();
    }

    function addFiles(newFiles) {
        var allowed = ['image/jpeg', 'image/png', 'image/webp'];
        newFiles.forEach(function(file) {
            if (!allowed.includes(file.type)) return;
            if (file.size > 5 * 1024 * 1024) { alert(file.name + ' ist zu groß (max. 5 MB).'); return; }
            if (selectedFiles.length >= 1) { selectedFiles = []; }
            selectedFiles.push(file);
        });
        renderPreviews();
    }

    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', function() { fileInput.click(); });

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            addFiles(Array.from(e.dataTransfer.files));
        });

        fileInput.addEventListener('change', function() {
            addFiles(Array.from(fileInput.files));
            fileInput.value = ''; // damit beim selben bild nochmal feuert 
        });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        var submitBtn = document.getElementById('vehicleSubmitBtn');
        var errorDiv  = document.getElementById('vehicleError');
        if (errorDiv) errorDiv.style.display = 'none'; // wenn von vorher noch da dann verstecken 

        var valid        = true;
        var firstInvalid = null; // scroll hierhin; siehe unten 
        form.querySelectorAll('[required]').forEach(function(f) {
            if (!f.value.trim()) {
                f.classList.add('invalid');
                f.classList.remove('field-ok');
                valid = false;
                if (!firstInvalid) firstInvalid = f; // rot setzen 
            } else {
                f.classList.remove('invalid'); // grün setzen
                f.classList.add('field-ok');
            }
        });

        if (!valid) {
            if (errorDiv) {
                errorDiv.textContent = 'Bitte füllen Sie alle Pflichtfelder (*) aus.';
                errorDiv.style.display = 'block';
            }
            if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (selectedFiles.length === 0) {
            if (errorDiv) {
                errorDiv.textContent = 'Bitte laden Sie ein Foto des Fahrzeugs hoch.';
                errorDiv.style.display = 'block';
            }
            document.getElementById('uploadArea').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (submitBtn) { submitBtn.disabled = true; submitBtn.value = 'Wird eingereicht …'; }

        var fd = new FormData();
        fd.append('make',      document.getElementById('sell-make').value.trim());
        fd.append('model',     document.getElementById('sell-model').value.trim());
        fd.append('year',      document.getElementById('sell-year').value);
        fd.append('km',        document.getElementById('sell-km').value);
        fd.append('fuel',      document.getElementById('sell-fuel').value);
        fd.append('gearbox',   document.getElementById('sell-gearbox').value);
        fd.append('power',     document.getElementById('sell-power').value);
        fd.append('antrieb',   document.getElementById('sell-antrieb').value);
        fd.append('type',      document.getElementById('sell-type').value);
        fd.append('condition', document.getElementById('sell-condition').value);
        fd.append('price',     document.getElementById('sell-price').value);
        fd.append('desc',      document.getElementById('sell-desc').value.trim());
        fd.append('name',      document.getElementById('sell-name').value.trim());
        fd.append('email',     document.getElementById('sell-email').value.trim());
        fd.append('phone',     document.getElementById('sell-phone').value.trim());
        selectedFiles.forEach(function(file) { fd.append('images[]', file); });

        try {
            const r    = await fetch(BASE_URL + '/api/listings/create', { method: 'POST', body: fd });
            const data = await r.json();

            if (data.success) {
                form.reset();
                selectedFiles = [];
                renderPreviews();
                form.style.display = 'none';
                var successDiv = document.getElementById('vehicleSuccess');
                if (successDiv) {
                    successDiv.textContent = 'Ihr Inserat wurde erfolgreich eingereicht und wird innerhalb von 24 Stunden geprüft.';
                    successDiv.style.display = 'block';
                    successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                if (errorDiv) {
                    errorDiv.textContent = data.message || 'Einreichen fehlgeschlagen. Bitte versuchen Sie es erneut.';
                    errorDiv.style.display = 'block';
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        } catch (err) {
            if (errorDiv) {
                errorDiv.textContent = 'Netzwerkfehler – bitte Seite neu laden und erneut versuchen.';
                errorDiv.style.display = 'block';
            }
        } finally {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.value = 'Inserat einreichen'; }
        }
    });
}
