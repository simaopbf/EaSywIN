<?php
session_start();

$check_in=$_POST['date_in'];
$check_out=$_POST['date_out'];
$username= $_SESSION['username'];
$transport_type=$_POST['transport_type'];
$n_guests=$_post['number_guests'];

//passar o id para o form senao nao vai funcionar 
function insertReservation($check_in, $check_out, $username, $transport_type, $n_guests)
{
  global $dbh;
  $stmt = $dbh->prepare('INSERT INTO reservation (date_in,date_out,transportation_type,guest,number_of_guests) VALUES (?, ?, ?, ?,?)');
  $stmt->execute(array($check_in, $check_out, $username, $transport_type, $n_guests));
}



try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.id =Accommodation.city INNER JOIN Ad ON Ad.accommodation= Accommodation.id WHERE Accommodation.id=:id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $accom = $stmt->fetchAll();
    if($check_in>=$accom[0]['date_on'] && $check_out<=$accom[0]['date_off']){
        insertReservation($check_in, $check_out, $username, $transport_type, $n_guests);
    }
    else{
        $_SESSION['msg'] = 'Check in or Check out out of range';
    }

}catch (PDOException $e) {
    $_SESSION['msg'] = 'Error: ' . $e->getMessage();
  }




?>