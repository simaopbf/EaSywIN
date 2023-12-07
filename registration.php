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
                <a href="homepage.php">EasyWIN</a>
            </div>
          
        </div>
    </nav>

    <div class="wrapper">
        <form action="process-signup.php" method="post">
            <h1>Sign Up</h1>

            <div class="image-container">
                <label for="profile-image-input" class="image-label">
                    <img id="profile-image" src="profile.png" alt="Profile Image">
                </label>
                <input type="file" id="profile-image-input" style="display: none;" accept="image/*" onchange="displayImage(this)">
            </div>


            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Your username" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Your e-mail" required>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box">
                <input type="tel"  id="phone" name="phone" placeholder="Your telephone" required>
                <i class='bx bxs-phone' ></i>
            </div>

            <?php
                if (isset($error_message)) {
                    echo '<p class="error-message">' . $error_message . '</p>';
                }
            ?>
            
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Your password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" class="btn">Sign Up</button>

            <div class="register-link">
                <p>Do you already have an account? <a href="login.php">Login</a></p>
            </div>

            <script>
                function displayImage(input) {
                    var file = input.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById('profile-image').src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            </script>
        </form>
    </div>
</body>

<html>