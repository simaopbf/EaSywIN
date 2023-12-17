<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$host_username = $_SESSION['username'];

    try {
        $dbh = new PDO('sqlite:sql/database.db');
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        // Retrieve user ID
        $stmt_user = $dbh->prepare('SELECT * FROM User WHERE username = ?');
        $stmt_user->execute([$host_username]);
        $user = $stmt_user->fetch();

        $host_id = $user['username']; // Use the username as the host_id

        // Retrieve accommodations associated with the host
        $stmt_accommodations = $dbh->prepare('SELECT * FROM Accommodation WHERE host_ac = ?');
        $stmt_accommodations->execute([$host_id]);
        $accommodations = $stmt_accommodations->fetchAll();
    } catch (PDOException $e) {
        $error_msg = $e->getMessage();
    }
?>