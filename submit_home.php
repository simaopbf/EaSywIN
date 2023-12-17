<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_home'])) {
    try {
        $dbh = new PDO('sqlite:sql/database.db');
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve user information
        $stmt_user = $dbh->prepare('SELECT * FROM User WHERE username = ?');
        $stmt_user->execute([$_SESSION['username']]);
        $user = $stmt_user->fetch();

        // Retrieve form data
        $address = $_POST['address']; // Add other form fields as needed

        // Insert data into Accommodation table
        $stmt_insert = $dbh->prepare('INSERT INTO Accommodation (host_ac, address) VALUES (?, ?)');
        $stmt_insert->execute([$user['username'], $address]);

        // Check if the insertion was successful
        if ($stmt_insert->rowCount() > 0) {
            // Successful submission
            $_SESSION['msg'] = "Home submitted successfully!";
        } else {
            // Failed submission
            $_SESSION['msg'] = "Failed to submit home. Please try again.";
        }
        
        // Redirect back to the profile page
        header("Location: profile.php");
        exit();

    } catch (PDOException $e) {
        // Handle database error
        $error_msg = $e->getMessage();
        $_SESSION['msg'] = "Database error: $error_msg";
        header("Location: profile.php");
        exit();
    }
} else {
    // Redirect to the profile page if the form is not submitted
    header("Location: profile.php");
    exit();
}