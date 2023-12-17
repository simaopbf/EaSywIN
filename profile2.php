<?php
session_start();

$msg = $_SESSION['msg'];
unset($_SESSION['msg']);

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user information
    $stmt_user = $dbh->prepare('SELECT * FROM User WHERE username = ?');
    $stmt_user->execute([$_SESSION['username']]);
    $user = $stmt_user->fetch();

    // Retrieve user accommodations
    $stmt_accommodations = $dbh->prepare('SELECT * FROM Accommodation WHERE host_ac = ?');
    $stmt_accommodations->execute([$user['username']]);
    $accommodations = $stmt_accommodations->fetchAll();
} catch (PDOException $e) {
    $error_msg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- Header Section -->
    <div class="header">
        <nav>
            <div class="menu">
                <div class="logo">
                    <a href="homepage.php">EasyWIN</a>
                </div>
                <ul>
                    <li><a href="#">Maps</a> </li>
                    <li><a href="#howitworks">How it works</a> </li>
                    <?php if (!isset($_SESSION['username'])) { ?>
                        <li><a class="backgroundcolor" href="login.php">Login/ Sign up</a> </li>
                    <?php } else { ?>
                        <li><a class="backgroundcolor" href="profile.php"><i class='bx bxs-user'></i> Profile </a> </li>
                    <?php } ?>

                    <?php if (isset($msg)) { ?>
                        <p><?php echo $msg ?></p>
                    <?php }?>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content Section -->
    <div class="header_wrapper">
        <!-- Left Column Section (User Info) -->
        <header></header>
        <div class="cols_container">
            <div class="left_col">
            <div class ="img_container">
                    <img src="camera.jpg" alt="User">
                    <span></span>
                </div>
                <h2>Username</h2>
                <p>E-mail</p>
                <p>Telephone</p>
                <?php if(!isset($_SESSION['$username'])){ ?>
                    <form action="logout.php" method="post">
                    <button>Logout </button>
                <?php } ?>
                
                <a href="accommodation.php"><button>Add Accommodation</button></a>

                <ul class="about">
                    <li><span>25</span>Friends</li>
                    <li><span>2</span>Homes</li>
                </ul>

                <div class="content">
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dolor maxime corporis eum natus, assumenda deleniti fugit nisi atque aut adipisci, animi, distinctio voluptas ducimus eligendi. Molestias sunt quo aliquam porro.
                    </p>
                 
                    <ul>
                        <li><i class="fab fa-twitter"></i></li>
                        <li><i class="fab fa-pinterest"></i></li>
                        <li><i class="fab fa-facebook"></i></li>
                        <li><i class="fab fa-instagram"></i></li>
                    </ul>
                </div>
                
            </div>

            <!-- Right Column Section (Accommodations) -->
            <div class="right_col">
                <nav class="right-column-nav">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Friends</a></li>
                        <li><a href="#">Reservations</a></li>
                    </ul>
                    <button>Follow</button>
                </nav>

                <div class="Home">
                    <?php foreach ($accommodations as $accommodation) : ?>
                        <img src="pexels-patrik-felker-6220559.jpg" alt="Accommodation Photo">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
