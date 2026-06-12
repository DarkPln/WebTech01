<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($fahrzeug['name']) ?> – Fahrzeugdaten</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #fff;
            padding: 32px 40px;
        }

        /* ── Header ── */
        .pdf-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 2px solid #cc0000;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .pdf-logo { font-size: 28px; font-weight: 900; letter-spacing: -1px; }
        .pdf-logo span { color: #cc0000; }
        .pdf-meta { text-align: right; font-size: 11px; color: #666; }

        /* ── Fahrzeugname ── */
        .pdf-title { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .pdf-subtitle { font-size: 13px; color: #555; margin-bottom: 20px; }

        /* ── Bild ── */
        .pdf-img {
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            background: #f2f2f2;
            border-radius: 6px;
            margin-bottom: 24px;
            display: block;
        }

        /* ── Tabelle ── */
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .pdf-table th {
            background: #1a1a1a;
            color: #fff;
            text-align: left;
            padding: 8px 12px;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .pdf-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #e8e8e8;
            vertical-align: top;
        }
        .pdf-table tr:nth-child(even) td { background: #f9f9f9; }
        .pdf-table .label { color: #555; width: 38%; }
        .pdf-table .value { font-weight: 600; }

        /* ── Preis ── */
        .pdf-price-box {
            background: #1a1a1a;
            color: #fff;
            padding: 14px 18px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .pdf-price-label { font-size: 12px; color: #aaa; }
        .pdf-price-value { font-size: 24px; font-weight: 700; color: #cc0000; }
        .pdf-price-note  { font-size: 11px; color: #aaa; }

        /* ── Beschreibung ── */
        .pdf-desc-title { font-weight: 700; margin-bottom: 6px; font-size: 13px; }
        .pdf-desc { color: #444; line-height: 1.6; font-size: 12px; margin-bottom: 24px; }

        /* ── Footer ── */
        .pdf-footer {
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }

        /* Drucken-Button (wird beim Drucken ausgeblendet) */
        .print-btn {
            display: block;
            margin: 0 auto 24px;
            padding: 10px 28px;
            background: #cc0000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="pdf-header">
    <div class="pdf-logo">AUTO<span>24</span></div>
    <div class="pdf-meta">
        Fahrzeugdatenblatt<br>
        Erstellt am <?= date('d.m.Y') ?>
    </div>
</div>

<button class="print-btn" onclick="window.print()">Als PDF speichern / Drucken</button>

<div class="pdf-title"><?= htmlspecialchars($fahrzeug['marke'] . ' ' . $fahrzeug['modell']) ?></div>
<div class="pdf-subtitle">
    Baujahr <?= (int)$fahrzeug['baujahr'] ?> &nbsp;·&nbsp;
    <?= htmlspecialchars($fahrzeug['kraftstoff']) ?> &nbsp;·&nbsp;
    <?= htmlspecialchars(ucfirst($fahrzeug['kategorie'] ?? 'Gebrauchtfahrzeug')) ?>
</div>

<?php if (!empty($fahrzeug['imagepath'])): ?>
<img class="pdf-img"
     src="<?= htmlspecialchars($fahrzeug['imagepath']) ?>"
     alt="<?= htmlspecialchars($fahrzeug['name']) ?>">
<?php endif; ?>

<div class="pdf-price-box">
    <div>
        <div class="pdf-price-label">Verkaufspreis</div>
        <div class="pdf-price-value"><?= number_format($fahrzeug['preis'], 0, ',', '.') ?> €</div>
    </div>
    <div class="pdf-price-note">inkl. 19 % MwSt.</div>
</div>

<table class="pdf-table">
    <tr><th colspan="2">Fahrzeugdaten</th></tr>
    <tr><td class="label">Marke</td>          <td class="value"><?= htmlspecialchars($fahrzeug['marke']) ?></td></tr>
    <tr><td class="label">Modell</td>         <td class="value"><?= htmlspecialchars($fahrzeug['modell']) ?></td></tr>
    <tr><td class="label">Baujahr</td>        <td class="value"><?= (int)$fahrzeug['baujahr'] ?></td></tr>
    <tr><td class="label">Kraftstoff</td>     <td class="value"><?= htmlspecialchars($fahrzeug['kraftstoff']) ?></td></tr>
    <tr><td class="label">Kilometerstand</td> <td class="value"><?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km</td></tr>
    <tr><td class="label">Leistung</td>       <td class="value"><?= (int)$fahrzeug['leistung_ps'] ?> PS</td></tr>
    <tr><td class="label">Antrieb</td>        <td class="value"><?= htmlspecialchars($fahrzeug['antrieb']) ?></td></tr>
    <tr><td class="label">Getriebe</td>       <td class="value"><?= htmlspecialchars($fahrzeug['gearbox'] ?? '–') ?></td></tr>
    <tr><td class="label">Kategorie</td>      <td class="value"><?= htmlspecialchars(ucfirst($fahrzeug['kategorie'] ?? '–')) ?></td></tr>
</table>

<?php if (!empty($fahrzeug['beschreibung'])): ?>
<div class="pdf-desc-title">Beschreibung</div>
<div class="pdf-desc"><?= nl2br(htmlspecialchars($fahrzeug['beschreibung'])) ?></div>
<?php endif; ?>

<div class="pdf-footer">
    AUTO24 GmbH &nbsp;·&nbsp; Altschauerberg 8, 85049 Ingolstadt &nbsp;·&nbsp;
    info@auto24.de &nbsp;·&nbsp; +49 (0) 841 / 123 456<br>
    Mo–Fr 8:00–18:00 Uhr &nbsp;·&nbsp; Geschäftsführer: Lukas Neumayer, Niclas Reuter, Tim Höhn
</div>


</body>
</html>
