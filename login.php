<?php

session_start();

$file_path = __DIR__ . "/data/campers.json";
$json_data = file_get_contents($file_path);
$campers = json_decode($json_data, true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $login_successful = false;

foreach ($campers as $camper) {
    if ($camper["username"] == $username &&
        password_verify($password, $camper["password"])) {

        session_regenerate_id(true);
        
        $_SESSION["logged_in"] = true;
        $_SESSION["camper_username"] = $username;
        $_SESSION["camper_first_name"] = $camper["first_name"];

        $login_successful = true;
        break;
    }
}

if ($login_successful) {
    header("Location: camper_account.php");
    exit();
} else {
    $login_message = "Invalid username or password.";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login | Moonlight Pines Campground</title>
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
</nav>

<main>
    <h2>Camper Login</h2>

    <?php
    if (isset($login_message)) {
        echo "<p>" . $login_message . "</p>";
}
?>

    <p>
        The Camper Login page allows returning Moonlight Pines campers to access
        their camper account. Registered campers can enter the username and
        password they created during registration to log in. This provides
        returning campers with a convenient way to access their account
        information without completing the registration process again.
    </p>

    <p>
        To log in, campers will enter the username and password associated with
        their Moonlight Pines camper account. The login information will be
        processed to determine whether the information entered is valid. If the
        login is successful, the camper will be able to continue to the Camper
        Account page.
    </p>

    <p>
        Campers who have not created an account can visit the Register/Reserve page
        to sign up and provide information for their stay. After creating an account,
        they can return to this page and use their login information to access the
        camper area. This helps keep the registration process for new campers
        separate from account access for returning campers.
    </p>

    <h3>Login to Your Camper Account</h3>

    <form method="post" action="login.php">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>

    </form>
</main>
</body>
</html>