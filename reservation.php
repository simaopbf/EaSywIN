<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);


try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
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
             <h1>Make Reservations</h1>
             <div class= "divide">

                <div class= "house_sec_1">
                   <h3>House (friend)</h3>
                  <img src="airbnb alugar.png">
                </div>
                <div class= "house_sec_2">
                    <h4>City: (city)</h4>
                    <h4>Weather: fjdks</h4>
                    <h4>Capacity: capacity</h4>
                    <button>Reserve</button>


            
                
                </div>
            </div>
           
        </div>
    </div>                
</body>

</html>