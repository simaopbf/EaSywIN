<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$host_id = $_SESSION['username'];
try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve accommodations associated with the host
    $stmt_accommodations = $dbh->prepare('SELECT * FROM Accommodation WHERE host_ac = ?');
    $stmt_accommodations->execute([$host_id]);
    $accommodations = $stmt_accommodations->fetchAll();

    // Retrieve form data
    $accommodation_id = $_POST['accommodation_id'];
    $date_on = $_POST['date_on'];
    $date_off = $_POST['date_off'];
    $descrip = $_POST['descrip'];
    $priv_publ = $_POST['priv_publ'];

    var_dump($accommodations[0]['city']);
    // Insert data into the Ad table
    $stmt_insert = $dbh->prepare('INSERT INTO Ad (host_ad, city, date_on, date_off, descrip, priv_publ, accommodation) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt_insert->execute([$host_id, $accommodations[0]['city'], $date_on, $date_off, $descrip, $priv_publ, $accommodation_id]);

    // Check if the insertion was successful
    if ($stmt_insert->rowCount() > 0) {
        $msg = "Announcement submitted successfully!";
    } else {
        $msg = "Failed to submit announcement. Please try again.";
    }

    include("announce.php");
    exit();
} catch (PDOException $e) {
    // Handle database error
    $error_msg = $e->getMessage();
    $_SESSION['msg'] = "Database error: $error_msg";
    include("announce.php");
    exit();
}
?>

<!-- <?php if (isset($msg)) : ?>
    <div class="message">
        <?= $msg ?>
    </div>
<?php endif; ?>  -->