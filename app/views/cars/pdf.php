<!DOCTYPE html>
<!-- Lukas: pdf view -->

<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo  htmlspecialchars($fahrzeug['name']) ?> - Fahrzeugdaten</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
</head>
<body class="pdf-page">

<div class="pdf-header">
    <div class="pdf-logo">AUTO<span>24</span></div>
    <div class="pdf-meta">
        Fahrzeugdatenblatt<br>
        Erstellt am <?php echo  date('d.m.Y') ?>
    </div>
</div>

<!-- Lukas: Download Fkt --> 
<button class="print-btn" onclick="window.print()">Als PDF speichern / Drucken</button>

<div class="pdf-title"><?php echo  htmlspecialchars($fahrzeug['marke'] . ' ' . $fahrzeug['modell']) ?></div>
<div class="pdf-subtitle">
    Baujahr <?php echo  (int)$fahrzeug['baujahr'] ?>  -
    <?php echo  htmlspecialchars($fahrzeug['kraftstoff']) ?>  -
    <?php echo  htmlspecialchars(ucfirst($fahrzeug['kategorie'] ?? 'Gebrauchtfahrzeug')) ?>
</div>

<?php if (!empty($fahrzeug['imagepath'])): ?>
<img class="pdf-img"
     src="<?php echo  htmlspecialchars($fahrzeug['imagepath']) ?>"
     alt="<?php echo  htmlspecialchars($fahrzeug['name']) ?>">
<?php endif; ?>

<div class="pdf-price-box">
    <div>
        <div class="pdf-price-label">Verkaufspreis</div>
        <div class="pdf-price-value"><?php echo  number_format($fahrzeug['preis'], 0, ',', '.') ?> €</div>
    </div>
    <div class="pdf-price-note">inkl. 19 % MwSt.</div>
</div>

<table class="pdf-table">
    <tr><th colspan="2">Fahrzeugdaten</th></tr>
    <tr><td class="label">Marke</td>          <td class="value"><?php echo  htmlspecialchars($fahrzeug['marke']) ?></td></tr>
    <tr><td class="label">Modell</td>         <td class="value"><?php echo  htmlspecialchars($fahrzeug['modell']) ?></td></tr>
    <tr><td class="label">Baujahr</td>        <td class="value"><?php echo  (int)$fahrzeug['baujahr'] ?></td></tr>
    <tr><td class="label">Kraftstoff</td>     <td class="value"><?php echo  htmlspecialchars($fahrzeug['kraftstoff']) ?></td></tr>
    <tr><td class="label">Kilometerstand</td> <td class="value"><?php echo  number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km</td></tr>
    <tr><td class="label">Leistung</td>       <td class="value"><?php echo  (int)$fahrzeug['leistung_ps'] ?> PS</td></tr>
    <tr><td class="label">Antrieb</td>        <td class="value"><?php echo  htmlspecialchars($fahrzeug['antrieb']) ?></td></tr>
    <tr><td class="label">Getriebe</td>       <td class="value"><?php echo  htmlspecialchars($fahrzeug['gearbox'] ?? '-') ?></td></tr>
    <tr><td class="label">Kategorie</td>      <td class="value"><?php echo  htmlspecialchars(ucfirst($fahrzeug['kategorie'] ?? '-')) ?></td></tr>
</table>

<?php if (!empty($fahrzeug['beschreibung'])): ?>
<div class="pdf-desc-title">Beschreibung</div>
<div class="pdf-desc"><?php echo  nl2br(htmlspecialchars($fahrzeug['beschreibung'])) ?></div>
<?php endif; ?>

<div class="pdf-footer">
    AUTO24 GmbH  - Altschauerberg 8, 85049 Ingolstadt  -
    info@auto24.de  - +49 (0) 841 / 123 456<br>
    Mo-Fr 8:00-18:00 Uhr  - Geschäftsführer: Lukas Neumayer, Niclas Reuter, Tim Höhn
</div>


</body>
</html>
