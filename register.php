<?php

function calculateNights($arrival_date, $departure_date) {
    $arrival = new DateTime($arrival_date);
    $departure = new DateTime($departure_date);

    if ($departure <= $arrival) {
        return 0;
    }

    $difference = $arrival->diff($departure);

    return $difference->days;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $arrival_date = $_POST["arrival_date"];
    $departure_date = $_POST["departure_date"];
    $num_nights = calculateNights($arrival_date, $departure_date);
    if ($num_nights == 0) {
        $date_message = "Departure date must be after the arrival date.";
    }
    $num_campers = $_POST["num_campers"];
    $accommodation = $_POST["accommodation"];
    $activities = isset($_POST["activities"]) ? $_POST["activities"] : [];
    $amenities = isset($_POST["amenities"]) ? $_POST["amenities"] : [];
    $comments = $_POST["comments"];
    $activity_list = "";

    foreach ($activities as $activity) {
        $activity_list .= $activity . " ";
    }

    $amenity_list = "";

    foreach ($amenities as $amenity) {
    $amenity_list .= $amenity . " ";
}

    if ($num_nights > 0) {

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $camper_account = [
    "first_name" => $first_name,
    "last_name" => $last_name,
    "email" => $email,
    "username" => $username,
    "password" => $hashed_password
    ];

    $file_path = __DIR__ . "/data/campers.json";
    $json_data = file_get_contents($file_path);
    $campers = json_decode($json_data, true);
    $username_exists = false;

    foreach ($campers as $camper) {
        if ($camper["username"] == $username) {
            $username_exists = true;
            break;
    }
}
    if ($username_exists) {
        $save_error = "This username is already taken. Please choose another.";
    } else {
        $campers[] = $camper_account;

        if (file_put_contents($file_path, json_encode($campers, JSON_PRETTY_PRINT), LOCK_EX) === false) {
           $save_error = "Unable to save camper account.";
    }
}
    if (isset($save_error)) {
        $message = $save_error;
    } else {
        setcookie("camper_name", $first_name, time() + (86400 * 30), "/");
        $message = "Thank you, " . $first_name . "! Your camper account has been created.";
}
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Register/Reserve | Moonlight Pines Campground</title>
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
    <h2>Register for Your Stay</h2>

    <?php
    if (isset($message)) {
        echo "<p>" . $message . "</p>";
        echo "<p>Activities selected: " . $activity_list . "</p>";
        echo "<p>Amenities selected: " . $amenity_list . "</p>";
        
        if ($num_nights > 0) {
           echo "<p>Length of stay: " . $num_nights . " nights</p>";
        }
    }
    ?>

    <?php
    if (isset($date_message)) {
        echo "<p>" . $date_message . "</p>";
    }
    ?>

    <?php
    if (isset($_COOKIE["camper_name"])) {
       echo "<p>Welcome back, " . $_COOKIE["camper_name"] . "!</p>";
    }
    ?>

    <p>
        The Register/Reserve page allows new campers to create a Moonlight Pines
        camper account and provide information for an upcoming stay. Campers can
        enter their contact information, choose their preferred accommodation,
        and provide the dates they plan to visit. Creating an account helps
        Moonlight Pines organize camper information and prepare for each guest's
        stay.
    </p>

    <p>
        During registration, campers will provide their name, email address, phone
        number, username, and password to create their camper account. They will also
        select their arrival and departure dates, number of campers, and preferred
        accommodation type. This information allows campers to create an account
        while also providing the basic details needed to plan their visit.
    </p>

    <p>
        Campers can also select activity and amenity preferences and provide any
        special requests or comments related to their visit. After the registration
        form is completed, the information will be processed so the camper can
        receive confirmation that the form was submitted successfully. Returning
        campers will be able to use the Login page to access their camper account.
    </p>

    <h3>Create Your Camper Account</h3>

    <form method="post" action="register.php">

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Phone:</label>
    <input type="tel" id="phone" name="phone" required>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="arrival_date">Arrival Date:</label>
    <input type="date" id="arrival_date" name="arrival_date" required>

    <label for="departure_date">Departure Date:</label>
    <input type="date" id="departure_date" name="departure_date" required>

    <label for="num_campers">Number of Campers:</label>
    <input type="number" id="num_campers" name="num_campers" min="1" required>

    <label for="accommodation">Accommodation Type:</label>
    <select id="accommodation" name="accommodation" required>
        <option value="">Select an option</option>
        <option value="Tent Site">Tent Site</option>
        <option value="RV Site">RV Site</option>
        <option value="Cabin">Cabin</option>
    </select>

    <p>Activity Preferences:</p>

    <input type="checkbox" id="hiking" name="activities[]" value="Hiking">
    <label for="hiking">Hiking</label>

    <input type="checkbox" id="fishing" name="activities[]" value="Fishing">
    <label for="fishing">Fishing</label>

    <input type="checkbox" id="kayaking" name="activities[]" value="Kayaking">
    <label for="kayaking">Kayaking</label>

    <p>Amenity Preferences:</p>

    <input type="checkbox" id="picnic_areas" name="amenities[]" value="Picnic Areas">
    <label for="picnic_areas">Picnic Areas</label>

    <input type="checkbox" id="fire_pits" name="amenities[]" value="Fire Pits">
    <label for="fire_pits">Fire Pits</label>

    <input type="checkbox" id="playground" name="amenities[]" value="Playground">
    <label for="playground">Playground</label>

    <input type="checkbox" id="wifi" name="amenities[]" value="Wi-Fi">
    <label for="wifi">Wi-Fi</label>

    <label for="comments">Special Requests or Comments:</label>
    <textarea id="comments" name="comments" rows="4" cols="40"></textarea>

    <button type="submit">Create Camper Account</button>
 
    </form>
</main>

</body>
</html>