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
    <a href="index.php" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
        <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
        <button class="mode-btn" onclick="toggleMode()">Light</button>
    </div>
</nav>

<h1>Meine Merkliste</h1>

    <p>Keine Fahrzeuge in der Merkliste.</p>
    <a href="gebrauchtwagenList.php">Zurück zur Übersicht</a>

<script src="validation.js"></script>
</body>
</html>