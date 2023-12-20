<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);~

  $userImage = "images/users/" . $_SESSION['username'] . ".jpg";
  $defaultImage = "profile.png";

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (file_exists($userImage)) {
        $imageSource = $userImage;
    } else {
        $imageSource = $defaultImage;
    }

    $stmt = $dbh->prepare('SELECT email FROM User WHERE username = :username');
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $email = $user['email'];
    } else { 
        $email = 'N/A'; 
    }    
    
    $stmt = $dbh->prepare('SELECT phone_number FROM User WHERE username = :username');
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $phone = $user['phone_number'];
    } else { 
        $phone = 'N/A'; 
    }
    
    $stmt_accommodations = $dbh->prepare('SELECT * FROM Accommodation  WHERE host_ac = :username');
    $stmt_accommodations->bindParam(':username', $_SESSION['username']);
    $stmt_accommodations->execute();
    $accommodations = $stmt_accommodations->fetchAll();

    $home_count = count($accommodations);

    $stmt_followers = $dbh->prepare('SELECT * FROM Friend  WHERE user2_name = :username');
    $stmt_followers->bindParam(':username', $_SESSION['username']);
    $stmt_followers->execute();
    $followers = $stmt_followers->fetchAll();

    $followers_count = count($followers);

  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="widt=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="header">
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyWIN</a>
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

            </ul>
        </div>
    </nav>
    </div> 
        
    <div class = "header_wrapper">
        <header></header>
        <div class="cols_container">
            <div class ="left_col">
                <form action="process-signup.php" method="post" enctype="multipart/form-data">
                    <div class="image-container">
                    <img src="<?php echo $imageSource; ?>">
                    </div>
                </form>

                <h2><span><?php echo $_SESSION['username'] ?></span></h2>
                <p><?php echo $email; ?></p> 
                <p><?php echo $phone; ?></p>

                <div class = "buttons">
                    <a href="accommodation.php"><button>Add Accommodation</button></a>
                
                    <?php if(!isset($_SESSION['$username'])){ ?>
                        <form action="logout.php" method="post">
                            <button>Logout </button>
                        </form>
                    <?php } ?>

                </div>

                <ul class="about">
                    <li><span><?php echo $followers_count; ?></span><a href="followers.php">Followers</a></li>
                    <li><span><?php echo $home_count; ?></span>Homes</li>
                </ul>

                <div class="content">
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dolor maxime corporis eum natus, assumenda deleniti fugit nisi atque aut adipisci, animi, distinctio voluptas ducimus eligendi. Molestias sunt quo aliquam porro.
                    </p>
                 

                </div>
            </div>

            <div class="right_col">
                <nav class="right-column-nav">
                    
                    <a href="list_users.php"><button>Find friends</button></a>
                    <a href="#"><button>Reservations</button></a>
                </nav>

                <h2>Homes</h2>
                <div class="Home">
                    <?php foreach ($accommodations as $accommodation) : ?>
                        <?php
                            $imagePath = "images/accommod/" . $accommodation['address'] . ".png";
                            $defaultImageA = "profile.png";

                            if (file_exists($imagePath)) {
                                $imageSource = $imagePath;
                            } else {
                                $imageSource = $defaultImageA;
                            }
                        ?>
                        <img src="<?php echo $imageSource; ?>" alt="Accommodation Photo">
                    <?php endforeach; ?>
                </div>
            
            </div>
        </div>
    </div>
</body>

</html>