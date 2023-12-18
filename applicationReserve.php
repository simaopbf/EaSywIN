<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);

  $id=(int)$_GET['id'];

  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.id =Accommodation.city INNER JOIN Ad ON Ad.accommodation= Accommodation.id WHERE Accommodation.id=:id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $accom = $stmt->fetchAll();
    $stmt = $dbh->query('SELECT * FROM City');
    $city = $stmt->fetchAll();
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
<title>EasyWin Home</title>
<link rel="stylesheet" href="styleReservations.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<head>
<body>
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyWIN</a>
            </div>
            <ul>
            <?php if (!isset($_SESSION['username'])) { ?>   
                    <li><a class="backgroundcolor" href="login.php"> <i class='bx bxs-user'></i>  Login</a> </li>
                <?php } else { ?>
                        <li><a class="backgroundcolor" href="profile.php"><i class='bx bxs-user'></i>  Profile </a>  </li>
                <?php } ?>
                
    
            </ul>
        </div>
    </nav>
    

    </div>
    <div class="center">
        <div class="wrapper">
             <h1>Place Reservations</h1>
             <h1> <?php echo $id ?> </h1>
                 <div class= "divide">
                    <div class= "house_sec_1">
                    <h3>House: <?php echo $accom[0]['host_ad'] ?> </h3>
                   <img src="images/<?php echo $accom[0]['image']?>.png" alt="">
                    </div>
                    <div class= "house_sec_2">
                    <h4>City: <?php echo $accom[0]['name'] ?></h4>
                    <h4>Weather: <?php echo $accom[0]['meteorology'] ?></h4>
                    <h4>Capacity:<?php echo $accom[0]['capacity'] ?> </h4>
                    <h4>Average cost of living per day:<?php echo $accom[0]['average_cost_of_living'] ?> </h4>
                    
                    </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>                
</body>

</html>