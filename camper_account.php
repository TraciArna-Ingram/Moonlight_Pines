<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Camper Account | Moonlight Pines Campground</title>
</head>
<body>
    <header>
    <h1>Moonlight Pines Campground</h1>
    <p>Relax. Explore. Stay awhile.</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="campsites.php">Campsites</a>
    <a href="activities_amenities.php">Activities &amp; Amenities</a>
    <a href="register.php">Register/Reserve</a>
    <a href="login.php">Login</a>
    <a href="camper_account.php">Camper Account</a>
    <a href="logout.php">Sign Out</a>
</nav>

<main>
    <h2>Camper Account</h2>

    <?php
   if (isset($_SESSION["camper_first_name"])) {
    echo "<h3>Welcome, " . htmlspecialchars($_SESSION["camper_first_name"]) . "!</h3>";
}
?>

    <p>
        The Camper Account page gives returning Moonlight Pines campers a place
        to access information connected to their campground visit. After logging
        in, campers can use this page to review account and stay information.
        This provides a convenient location for returning campers to view details
        related to their Moonlight Pines experience.
    </p>

    <p>
        Camper account information can include details such as the camper's name,
        accommodation preference, arrival and departure dates, and selected
        activities or amenities. Keeping this information together allows campers
        to easily review the choices they made while planning their visit to
        Moonlight Pines Campground.
    </p>

    <p>
        The Camper Account page is designed for registered campers who have
        successfully logged in to the Moonlight Pines website. New visitors should
        first use the Register/Reserve page to create a camper account before using
        the Login page. This process helps guide new and returning campers to the
        appropriate area of the website.
    </p>

    <h3>Camper Information</h3>

    <p>

    <strong>Username:</strong>
    <?php echo $_SESSION["camper_username"]; ?>
    </p>
</main>

</body>
</html>