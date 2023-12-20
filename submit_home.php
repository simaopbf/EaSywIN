<?php
session_start();
$msg = $_SESSION['msg'];
unset($_SESSION['msg']);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

function saveAccommodPic($address) {
    move_uploaded_file($_FILES['accommod_pic']['tmp_name'], "images/accommod/$address.png");
}

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $username = $_SESSION['username'];
    // Retrieve user information
    // $stmt_user = $dbh->prepare('SELECT * FROM User WHERE username = ?');
    // $stmt_user->execute([$_SESSION['username']]);
    // $user = $stmt_user->fetch();
    

    // Retrieve form data
    $address = $_POST['address']; 
    $city = $_POST['city'];
    $capacity = $_POST['capacity'];
    $accommod_pic=$_POST['accommod_pic'];
    saveAccommodPic($address);
    // Get the ID of the selected city
    $stmt_get_city_id = $dbh->prepare('SELECT city_id FROM City WHERE name = ?');
    $stmt_get_city_id->execute([$city]);
    $city_id = $stmt_get_city_id->fetch();

    // Insert data into Accommodation table
    $stmt_insert = $dbh->prepare('INSERT INTO Accommodation (host_ac, address, city, capacity) VALUES (?, ?, ?, ?)');
    $stmt_insert->execute([$username, $address, $city_id['city_id'], $capacity]);
    
    // Check if the insertion was successful
    if ($stmt_insert->rowCount() > 0) {
        // Successful submission
        $_SESSION['msg'] = "Home submitted successfully!";
    } else {
        // Failed submission
        $_SESSION['msg'] = "Failed to submit home. Please try again.";
    }


    // // Insert data into Accommodation table
    // $stmt_insert = $dbh->prepare('INSERT INTO Accommodation (host_ac, address, city, capacity) VALUES (?, ?, ?, ?)');
    // $stmt_insert->execute([$user['username'], $address, $city, $capacity]);
    
    // Redirect back to the profile page
    include("profile.php");
    exit();

} catch (PDOException $e) {
    // Handle database error
    $error_msg = $e->getMessage();
    $_SESSION['msg'] = "Database error: $error_msg";
    include("accommodation.php");
    exit();
}

?>