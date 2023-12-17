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
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $accommodation_id = $_POST['accommodation_id'];
        $address = $_POST['address'];
        $date_on = $_POST['date_on'];
        $date_off = $_POST['date_off'];
        $descrip = $_POST['descrip'];
        $priv_publ = $_POST['priv_publ'];

        // Insert data into the Ad table
        $stmt_insert = $dbh->prepare('INSERT INTO Ad (host_ad, city, date_on, date_off, descrip, priv_publ, accomodation) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt_insert->execute([$host_id, $city, $date_on, $date_off, $descrip, $priv_publ, $accommodation_id]);

        // Check if the insertion was successful
        if ($stmt_insert->rowCount() > 0) {
            $msg = "Announcement submitted successfully!";
        } else {
            $msg = "Failed to submit announcement. Please try again.";
        }
    }
} catch (PDOException $e) {
    $error_msg = $e->getMessage();
}
?>

<?php if (isset($msg)) : ?>
    <div class="message">
        <?= $msg ?>
    </div>
<?php endif; ?>