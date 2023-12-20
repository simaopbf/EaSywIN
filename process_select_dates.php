<?php
session_start();

$check_in=$_POST['check_in'];
$check_out=$_POST['check_out'];
$username= $_SESSION['username'];
$transport_type=$_POST['transport_type'];
$n_guests=$_POST['n_guests'];
$id= $_POST['id'];
$city_departure= $_POST['city_departure'];
function insertReservation($check_in, $check_out,$ad_point_id, $username, $transport_type, $n_guests, $host, $capacity)
{
  global $dbh;
  $stmt = $dbh->prepare('INSERT INTO Reservation (date_in,date_out,ad_point_id, transportation_type,guest,number_of_guests, host, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
  $stmt->execute(array($check_in, $check_out,$ad_point_id, $transport_type, $username, $n_guests,$host,$capacity));
}

$duration= (strtotime($check_out)-strtotime($check_in)) / 86400;

function insertBudget($total, $duration, $id_reserv, $distance)
{

  global $dbh;
  $stmt = $dbh->prepare('INSERT INTO Budget (total, duration, ad_point_id, distance) VALUES (?,?, ?, ?)');
  $stmt->execute(array($total, $duration, $id_reserv, $distance));
}

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Retrieve Accommodations/Cities
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.city_id =Accommodation.city INNER JOIN Ad ON Ad.accommodation= Accommodation.id WHERE Accommodation.id=:id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $accom = $stmt->fetchAll();
    // Retrieve Trasnportation_types 
    $stmt = $dbh->prepare('SELECT * FROM Transportation_type WHERE transportation_name=:id');
    $stmt->bindValue(':id', $transport_type, PDO::PARAM_STR);
    $stmt->execute();
    $transportation_type = $stmt->fetchAll();
    // Retrieve City of Departure
    $stmt = $dbh->prepare('SELECT * FROM City WHERE name=:id');
    $stmt->bindValue(':id', $city_departure, PDO::PARAM_STR);
    $stmt->execute();
    $city_departure_array= $stmt->fetchAll();
    // Budget Calculations 
    $t_value=$transportation_type[0]['cost_per_km'];

    $distance = acos((sin(deg2rad($city_departure_array[0]['lat']))* sin(deg2rad($accom[0]['lat']))) + (cos(deg2rad($city_departure_array[0]['lat'])) * cos(deg2rad($accom[0]['lat']))) * (cos(deg2rad($accom[0]['lon'])-deg2rad($city_departure_array[0]['lon'])))) * 6371;
    
    $total= $t_value * $distance + $duration * $accom[0]['average_cost_of_living'];
    
    if( strtotime($check_in)>=strtotime($accom[0]['date_on']) && strtotime($check_out)<=strtotime($accom[0]['date_off']) &&  $n_guests<= $accom[0]['capacity'] && strtotime($check_in)<=strtotime($check_out)){
        insertReservation($check_in, $check_out, $accom[0]['ad_id'], $username, $transport_type, $n_guests,$accom[0]['host_ac'], $accom[0]['capacity']);
        insertBudget($total, $duration,$accom[0]['ad_id'], $distance);
        //$stmt = $dbh->prepare('DELETE FROM Ad WHERE ad_id =?');
        //$stmt-> execute([$accom[0]['ad_id']]);
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