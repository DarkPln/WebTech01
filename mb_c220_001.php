<!-- Lukas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mercedes-Benz C 220 - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $carTitle = "Mercedes-Benz C 220";
        $carDesc  = "Der Mercedes-Benz C 220 ist eine elegante Limousine mit hochwertiger Verarbeitung und modernen Assistenzsystemen.";
    ?>

    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
        </ul>
        <div class="nav-right">
            <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
            <button class="mode-btn" onclick="toggleMode()">Light</button>
        </div>
    </nav>

    <h1><?php echo $carTitle; ?></h1>
    <p><?php echo $carDesc; ?></p>

</body>
<!-- Lukas -->
