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
    $stmt = $dbh->query('SELECT * FROM Accommodation 
                        INNER JOIN City ON City.city_id =Accommodation.city INNER JOIN Ad ON Ad.accommodation=Accommodation.id
                        INNER JOIN Climate ON Climate.id_climate = City.meteorology');
    $accom = $stmt->fetchAll();
    $stmt = $dbh->query('SELECT * FROM Ad');
    $ad = $stmt->fetchAll();
    
    
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
<title>Reserve</title>
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
    <?php function ifConnectionExists($user1, $user2) { //function was not tried to verify friend connection
    try {
        $dbh = new PDO('sqlite:sql/database.db');
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare('SELECT COUNT(*) AS count FROM Friend WHERE (user1_name = ? AND user2_name = ?)');
        $stmt->execute([$user1, $user2]);

        $result = $stmt->fetch();

        return $result['count'] > 0;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
?>   
<?php $today = date("d/m/Y H:i:s", strtotime('today')); ?>
    </div>
    <div class="center">
        <div class="wrapper">
             <h1>Make Reservations</h1>
             
             <?php if ($error_msg == null) { 
                 foreach ($accom as $row) {?>

                    <div class= "divide">
                    <div class= "house_sec_1">
                    <h3>House: <?php echo $row['host_ac'] ?></h3>
                   <img src="images/accommod/<?php echo $row['address']?>.png" alt="">
                    </div>
                    <div class= "house_sec_2">
                    <h4>City: <?php echo $row['name'] ?></h4>
                    <a href="city.php?id=<?=$row['name']?>"><button>Reserve</button></a>
                    <h4>Weather: <?php echo $row['common_name'] ?></h4>
                        <?php
                            echo "<p>Cold Months: {$row['avg_cold']} Hot Months: {$row['avg_hot']} Precipitation: {$row['precipitation']}</p>";
                        ?>
                    <h4>Capacity: <?php echo $row['capacity'] ?> </h4>
                    <h4>id: <?php echo $row['id'] ?> </h4>
                    <h4>Average cost of living per day: <?php echo $row['average_cost_of_living'] ?> </h4>
                    <a href="applicationReserve.php?id=<?=$row['id']?>"><button>Reserve</button></a>
                    </div>
                    </div>
                <?php } ?>
             <?php }else {
          echo $error_msg;
             } ?>
                </div>
            </div>
           
        </div>
    </div>                
</body>

</html>