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
    <title> Sign Up</title>
    <link rel="stylesheet" href="styleregister.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .error-message {
            color: red !important;
            font-weight: bold;
        }
    </style>
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
        <form action="process-signup.php" method="post" enctype="multipart/form-data">
            <h1>Sign Up</h1>

            <?php if (isset($msg)) { ?>
                <p class="message"><?php echo $msg ?></p>  
            <?php } ?>

            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="E-mail" required>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box">
                <input type="tel"  id="phone" name="phone" placeholder="Telephone" required>
                <i class='bx bxs-phone' ></i>
            </div>

            <?php if (isset($error_message)) { ?>
                <p class="error-message"><?php echo $error_message ?></p>  
            <?php } ?>

            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="image-container">
                <label for="profile_pic">Upload your profile picture:</label>
                <input type="file" id="profile_pic" name="profile_pic">
            </div>

            <button type="submit" class="btn">Sign Up</button>

            <div class="register-link">
                <p>Do you already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</body>

<html>