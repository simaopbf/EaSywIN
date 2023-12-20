<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
  $userImage = "images/users/" . $_SESSION['username'] . ".jpg";
  $defaultImage = "profile.png";


try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->query('SELECT * FROM User');
    $user = $stmt->fetchAll();
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.city_id =Accommodation.city INNER JOIN Ad ON Ad.accommodation= Accommodation.id INNER JOIN Reservation ON Reservation.ad_point_id=Ad.ad_id   INNER JOIN Budget ON Budget.ad_point_id = Ad.ad_id  WHERE Reservation.guest=?');
    $stmt->execute([$_SESSION['username'],]);
    $accom = $stmt->fetchAll();
    if (file_exists($userImage)) {
        $imageSource = $userImage;
    } else {
        $imageSource = $defaultImage;
    }


  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="eng">

</head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My reservations</title>
<link rel="stylesheet" href="styleReservations.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<head>
<body>
<nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyInn</a>
            </div>
            <ul>
                
                <?php if (!isset($_SESSION['username'])) { ?>   
                    <li><a class="backgroundcolor" href="login.php"> <i class='bx bxs-user'></i>  Login</a> </li>
                <?php } else { ?>
                        <li>
                            <a class="backgroundcolor" href="profile.php">
                            <img src="<?php echo $imageSource; ?>">
                                <span><?php echo $_SESSION['username'] ?></span>
                            </a>
                        </li>
                <?php } ?>    

                <?php if (isset($msg)) { ?>
                    <p><?php echo $msg ?></p>
                <?php } ?>
    
            </ul>
        </div>
</nav>
    

    </div>
    <div class="center">
        <div class="wrapper">
             <h1>My Reservations</h1>
             <?php if ($error_msg == null) { 
                 foreach ($accom as $row) {?>
                
                    <div class= "divide">
                    <div class= "house_sec_1">
                    <h3>House: <?php echo $row['host_ac'] ?></h3>
                   <img src="images/accommod/<?php echo $row['address']?>.png" alt="">
                    </div>
                    <div class= "house_sec_2">
                    <h4>City: <?php echo $row['name'] ?></h4>
                    <h4>Guests: <?php echo $row['number_of_guests'] ?> </h4>
                    <h4>Check in: <?php echo $row['date_in'] ?> </h4>
                    <h4>Check out: <?php echo $row['date_out'] ?> </h4>
                    <h4>Transportation type: <?php echo $row['transportation_type'] ?> </h4>
                    <h4>Estimated Budget: <?php echo number_format((float)$row['total'],2,",","") ?> €</h4>

                    </div>
                    </div>
                <?php } ?>
             <?php }else {
          echo $error_msg;
             } ?>
                <!-- <div class= "house_sec_1">
                   <h3>House (friend)</h3>
                  <img src="airbnb alugar.png">
                </div> -->
                


            
                
                </div>
            </div>
           
        </div>
    </div>                
</body>

</html>