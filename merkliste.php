<?php
// Cookie auslesen
$favorites = [];

if (isset($_COOKIE['favorites'])) {
    $decoded = urldecode($_COOKIE['favorites']);
    $favorites = json_decode($decoded, true);
    // true = als Array, nicht als Objekt
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Merkliste</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<nav>
    
</nav>

<h1>Meine Merkliste</h1>

    <p>Keine Fahrzeuge in der Merkliste.</p>
    <a href="gebrauchtwagenList.php">Zurück zur Übersicht</a>

<script src="validation.js"></script>
</body>
</html>