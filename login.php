<?php
  session_start();

  $error_message = $_SESSION['error_message'];
  $msg = $_SESSION['msg'];
  unset($_SESSION['error_message']);
  unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html lang="eng">


</head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="widt=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<head>

<body>
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyInn</a>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <form action="process_login.php" method="POST">
            <h1>Login</h1>
            <h2>
                <?php if (isset($msg)) { ?>
                    <p class="message"><?php echo $msg ?></p>  
                <?php } ?>
            </h2>

            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required><i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" class="btn">Login</button>

            <div class="register-link">
                <p>Don't have an account? <a href="registration.php">Sign up</a></p>
            </div>
        </form>
    </div>
    
</body>

<html>