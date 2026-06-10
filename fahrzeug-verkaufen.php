<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fahrzeug verkaufen - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php require_once 'nav.php'; ?>

    <main class="sell-main">
        <section class="home-hero">
            <div class="home-hero-content">
                <div class="home-badge">Kostenlos inserieren</div>
                <h1>Fahrzeug inserieren</h1>
                <p>Füllen Sie das Formular aus – wir prüfen Ihr Inserat und schalten es innerhalb von 24 Stunden frei.</p>
            </div>
        </section>

        <div class="sell-section">
            <div class="sell-form-card">

                <div id="vehicleSuccess" class="success-message" style="display:none;"></div>

                <form id="vehicleForm" novalidate>

                    <h2>Fahrzeugdaten</h2>

                    <div class="sell-form-grid">
                        <div>
                            <label for="sell-make">Marke *</label>
                            <input type="text" id="sell-make" name="make" placeholder="z. B. Audi" required>
                        </div>
                        <div>
                            <label for="sell-model">Modell *</label>
                            <input type="text" id="sell-model" name="model" placeholder="z. B. A4" required>
                        </div>
                    </div>

                    <div class="sell-form-grid">
                        <div>
                            <label for="sell-year">Baujahr *</label>
                            <input type="number" id="sell-year" name="year" placeholder="z. B. 2019" min="1990" max="2026" required>
                        </div>
                        <div>
                            <label for="sell-km">Kilometerstand *</label>
                            <input type="number" id="sell-km" name="km" placeholder="z. B. 45000" min="0" required>
                        </div>
                    </div>

                    <div class="sell-form-grid">
                        <div>
                            <label for="sell-fuel">Kraftstoffart *</label>
                            <select id="sell-fuel" name="fuel" required>
                                <option value="">– bitte wählen –</option>
                                <option value="benzin">Benzin</option>
                                <option value="diesel">Diesel</option>
                                <option value="elektro">Elektro</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="lpg">LPG (Autogas)</option>
                            </select>
                        </div>
                        <div>
                            <label for="sell-gearbox">Getriebe *</label>
                            <select id="sell-gearbox" name="gearbox" required>
                                <option value="">– bitte wählen –</option>
                                <option value="manuell">Manuell</option>
                                <option value="automatik">Automatik</option>
                                <option value="halbautomatik">Halbautomatik</option>
                            </select>
                        </div>
                    </div>

                    <div class="sell-form-grid">
                        <div>
                            <label for="sell-power">Leistung (PS)</label>
                            <input type="number" id="sell-power" name="power" placeholder="z. B. 150" min="1">
                        </div>
                        <div>
                            <label for="sell-antrieb">Antrieb</label>
                            <select id="sell-antrieb" name="antrieb">
                                <option value="">– bitte wählen –</option>
                                <option value="Frontantrieb">Frontantrieb (FWD)</option>
                                <option value="Hinterradantrieb">Hinterradantrieb (RWD)</option>
                                <option value="Allradantrieb">Allradantrieb (AWD/4x4)</option>
                            </select>
                        </div>
                    </div>

                    <div class="sell-form-grid">
                        <div>
                            <label for="sell-type">Fahrzeugtyp *</label>
                            <select id="sell-type" name="type" required>
                                <option value="">– bitte wählen –</option>
                                <option value="limousine">Limousine</option>
                                <option value="kombi">Kombi</option>
                                <option value="suv">SUV / Geländewagen</option>
                                <option value="cabrio">Cabrio / Roadster</option>
                                <option value="coupe">Coupé</option>
                                <option value="kleinwagen">Kleinwagen</option>
                                <option value="van">Van / Minibus</option>
                            </select>
                        </div>
                    </div>

                    <label for="sell-condition">Zustand *</label>
                    <select id="sell-condition" name="condition" required>
                        <option value="">– bitte wählen –</option>
                        <option value="neuwertig">Neuwertig</option>
                        <option value="sehr-gut">Sehr gut</option>
                        <option value="gut">Gut</option>
                        <option value="befriedigend">Befriedigend (leichte Gebrauchsspuren)</option>
                        <option value="schlecht">Mit Mängeln</option>
                    </select>

                    <h2>Preis &amp; Beschreibung</h2>

                    <label for="sell-price">Wunschpreis (€) *</label>
                    <input type="number" id="sell-price" name="price" placeholder="z. B. 18500" min="1" required>

                    <label for="sell-desc">Fahrzeugbeschreibung</label>
                    <textarea id="sell-desc" name="desc" placeholder="Beschreiben Sie Ihr Fahrzeug: Ausstattung, Besonderheiten, Mängel, Wartungshistorie …"></textarea>

                    <h2>Kontaktdaten</h2>

                    <label for="sell-name">Name *</label>
                    <input type="text" id="sell-name" name="name" placeholder="Vor- und Nachname" required>

                    <label for="sell-email">E-Mail-Adresse *</label>
                    <input type="email" id="sell-email" name="email" placeholder="ihre@email.de" required>

                    <label for="sell-phone">Telefonnummer</label>
                    <input type="tel" id="sell-phone" name="phone" placeholder="z. B. 0841 123456">

                    <p class="demo-hint">* Pflichtfelder. Ihr Inserat wird nach Einreichung geprüft und innerhalb von 24 Stunden freigeschaltet.</p>

                    <input type="submit" value="Inserat einreichen" id="vehicleSubmitBtn">

                </form>
            </div>
        </div>

    </main>

    <?php require_once 'footer.php'; ?>

    <script src="validation.js"></script>
</body>
</html>
<!-- Tim -->
