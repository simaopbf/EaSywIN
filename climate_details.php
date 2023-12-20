<?php
// climate_details.php

session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->query('SELECT * FROM User');
    $user = $stmt->fetchAll();
    $stmt = $dbh->query('SELECT * FROM Accommodation INNER JOIN City ON City.city_id =Accommodation.city');
    $accom = $stmt->fetchAll();
    $stmt = $dbh->query('SELECT * FROM City');
    $city = $stmt->fetchAll();
    $stmt = $dbh->query('SELECT * FROM Ad');
    $ad = $stmt->fetchAll();
    
    $cityId = $city['city_id'];

    // Query the Climate table based on the cityId
    $stmt = $dbh->prepare('SELECT * FROM Climate WHERE id = (SELECT meteorology FROM City WHERE city_id = :cityId)');
    $stmt->bindParam(':cityId', $cityId);
    $stmt->execute();
    $climateInfo = $stmt->fetchAll();

    // Display climate details
    echo "<h2>Climate name: {$climateInfo['common_name']}</h2>";
    echo "<p>Cold Months: {$climateInfo['avg_cold']}</p>";
    echo "<p>Hot Months: {$climateInfo['avg_hot']}</p>";
    echo "<p>Precipitation: {$climateInfo['precipitation']}</p>";
    
  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
}



