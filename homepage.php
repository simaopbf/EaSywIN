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

    if (file_exists($userImage)) {
        $imageSource = $userImage;
    } else {
        $imageSource = $defaultImage;  // Usa a imagem padrão se a imagem do usuário não existir
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
<title>EasyWin Home</title>
<link rel="stylesheet" href="home.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<head>
<body>
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyWIN</a>
            </div>
            <ul>
                <li><a href="#">Maps</a> </li>
                <li><a href="#howitworks">How it works</a> </li> 
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
    
    <div class="image_top">
        <img src="pexels-patrik-felker-6220559.jpg">
    </div>

    <div class="center">

        <div class="title">Visiting friends has never been this easy</div>
        <div>
            <ul>
            <?php if (!isset($_SESSION['username'])) { ?>   

                <li><a class="backgroundcolor"href="login.php">Announce</a></li>

                <li><a class="backgroundcolor"href="login.php">Reserve</a></li>
            <?php } else { ?>
                <li><a class="backgroundcolor"href="announce.php">Announce</a></li>

                <li><a class="backgroundcolor"href="reservation.php">Reserve</a></li>
            <?php } ?>

            <?php if (isset($msg)) { ?>
                <p><?php echo $msg ?></p>
            <?php } ?>
            </ul>
        </div>
    </div>
    
    <div id = "howitworks">
        <div class="divide">
            <div class="image_reserve"><img src="airbnb alugar.png"></div>
            <div class="TextHowItWorks">
                <h3>Make reservations</h3>
                <p>See which friends have available accomodations for you and decide for yourself based on weather, average costs and time of the year </p>
            </div>
        </div>
        <div class= "divide">
        <div class="TextHowItWorks">
                <h3>Make Announcements</h3>
                <p>Decide whenever you want to announce your house for your friends to visit you</p>
            </div>
             <div class="image_reserve"><img src="airbnb alugar.png"></div>
        </div>
        <div class="divide">
            <div class="image_reserve"><img src="airbnb alugar.png"></div>
            <div class="TextHowItWorks">
                <h3>Make a </h3>
                <p>See which friends have available accomodations for you and decide for yourself based on weather, average costs and time of the year </p>
            </div>
        </div>

    </div>
    <div class="heighttotal"></div>
</body>

</html>