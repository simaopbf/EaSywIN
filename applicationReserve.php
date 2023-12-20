<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);

  $id=(int)$_GET['id'];
  $userImage = "images/users/" . $_SESSION['username'] . ".jpg";
  $defaultImage = "profile.png";

  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM Accommodation INNER JOIN City ON City.city_id =Accommodation.city INNER JOIN Ad ON Ad.accommodation=Accommodation.id WHERE Accommodation.id=?');
    //$stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute([$id]);
    $accom = $stmt->fetch();
    $stmt =  $dbh->prepare('SELECT * FROM Transportation_type');
    $stmt->execute();
    $transp_type = $stmt->fetchAll();
    $stmt =  $dbh->prepare('SELECT * FROM City');
    $stmt->execute();
    $city_dep = $stmt->fetchAll();
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
<title>Place Reservations</title>
<link rel="stylesheet" href="applicationReservation.css">
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
             <h1>Place Reservations</h1>
                 <div class= "divide">
                    <div class= "house_sec_1">
                    <h3>House: <?php echo $accom['host_ad'] ?> </h3>
                   <img src="images/accommod/<?php echo $accom['address']?>.png" alt="">
                   <h4>City: <?php echo $accom['name'] ?></h4>
                        <h4>Weather: <?php echo $accom['meteorology'] ?></h4>
                        <h4>Capacity:<?php echo $accom['capacity'] ?> </h4>
                        <h4>Average cost of living per day:<?php echo $accom['average_cost_of_living'] ?> </h4>
                        <h4>Description:<?php echo $accom['descrip'] ?> </h4>
                        <h4>Check in available from: <?php echo $accom['date_on'] ?> </h4>
                        <h4>Check out available until: <?php echo $accom['date_off'] ?> </h4>
                    </div>
                    <div class= "house_sec_2">
                        <form action= "process_select_dates.php" method="post">
                        <?php if (isset($msg)) { ?>
                            <p class="message"><?php echo $msg ?></p>  
                        <?php } ?>
                        <div class="input_box">
                            <label for="check_in">Check in:</label>
                            <input type="date" min=<?php echo $accom['date_on']?> max=<?php echo $accom['date_off']?> id="check_in" name="check_in" required>
                        </div>
                        <div class="input_box">
                            <label for="check_out">Check out:</label>
                            <input type="date" min=<?php echo $accom['date_on']?> max=<?php echo $accom['date_off']?> id="check_out" name="check_out" required>
                        </div>

                        <div class="input_box">
                            <label for="transport_type">Method of transportation:</label>
                            <select id="transport_type" name="transport_type" required>
                            <?php foreach ($transp_type as $transportation_type) : ?>
                            <option value="<?= $transportation_type['transportation_name'] ?>"><?= $transportation_type['transportation_name'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                            
                        <div class="input_box">
                            <label for="city_departure">Departure City:</label>
                            <select id="city_departure" name="city_departure" required>
                            <?php foreach ($city_dep as $city_departure) : ?>
                            <option value="<?= $city_departure['name'] ?>"><?= $city_departure['name'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        

                        <div class="input_box">
                        <label for="n_guests">Number of guests:</label>
                        <input type="number" id="n_guests" name="n_guests" min="1" max="<?php echo $accom['capacity']?>">
                        </div>
                        
                        <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
                        <button type="submit" class="btn">Reserve</button>
                        </form>
                    </div>
                 </div>
                </div>
            </div>
           
        </div>
    </div>                
</body>

</html>