<?php
session_start();

$check_in=$_POST['check_in'];
$check_out=$_POST['check_out'];
$username= $_SESSION['username'];
$transport_type=$_POST['transport_type'];
$n_guests=$_POST['n_guests'];
$id= $_POST['id'];
$city_departure= 'Oporto';
function insertReservation($check_in, $check_out, $username, $transport_type, $n_guests,$capacity)
{
  global $dbh;
  $stmt = $dbh->prepare('INSERT INTO Reservation (date_in,date_out,transportation_type,guest,number_of_guests, capacity) VALUES (?, ?, ?, ?, ?, ?)');
  $stmt->execute(array($check_in, $check_out, $transport_type, $username, $n_guests,$capacity));
}

$duration= (strtotime($check_out)-strtotime($check_in)) / 86400;

function insertBudget($total, $duration, $distance)
{

  global $dbh;
  $stmt = $dbh->prepare('INSERT INTO Budget (total,duration,distance) VALUES (?, ?, ?)');
  $stmt->execute(array($total, $duration, $distance));
}



try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.city_id =Accommodation.city INNER JOIN Ad ON Ad.accommodation= Accommodation.id WHERE Accommodation.id=:id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $accom = $stmt->fetchAll();
    $stmt = $dbh->prepare('SELECT * FROM Transportation_type WHERE transportation_name=:id');
    $stmt->bindValue(':id', $transport_type, PDO::PARAM_STR);
    $stmt->execute();
    $transportation_type = $stmt->fetchAll();
    $stmt = $dbh->prepare('SELECT * FROM City WHERE name=:id');
    $stmt->bindValue(':id', $city_departure, PDO::PARAM_STR);
    $stmt->execute();
    $city_departure_array= $stmt->fetchAll();
    var_dump($city_departure_array[0]['lat']);
    $t_value=$transportation_type[0]['cost_per_km'];
    $distance = acos((sin(deg2rad($city_departure_array[0]['lat']))* sin(deg2rad($accom[0]['lat']))) + (cos(deg2rad($city_departure_array[0]['lat'])) * cos(deg2rad($accom[0]['lat']))) * (cos(deg2rad($accom[0]['lon'])-deg2rad($city_departure_array[0]['lon'])))) * 6371;
    $total= $t_value * $distance + $duration * $accom[0]['average_cost_of_living'];
    insertBudget($total, $duration, $distance);
    if( strtotime($check_in)>=strtotime($accom[0]['date_on']) && strtotime($check_out)<=strtotime($accom[0]['date_off']) &&  $n_guests<= $accom[0]['capacity'] && strtotime($check_in)<=strtotime($check_out)){
        insertReservation($check_in, $check_out, $username, $transport_type, $n_guests, $accom[0]['capacity']);
        include('homepage.php');
        die();  
    }
    else{
        $_SESSION['msg'] = 'Check in or Check out out of range';
        $redirectUrl = 'applicationReserve.php';
        $redirectUrl .= '?id=' . urlencode($id);
         header('Location:'. $redirectUrl);
        die();
    }

}catch (PDOException $e) {
    $_SESSION['msg'] = 'Error: ' . $e->getMessage();
    
    include('applicationReserve.php');
  die();
  }




?>