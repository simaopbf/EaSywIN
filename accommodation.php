<?php
session_start();

$userImage = "images/users/" . $_SESSION['username'] . ".jpg";
$defaultImage = "profile.png";

$msg = $_SESSION['msg'];
unset($_SESSION['msg']);

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->query('SELECT * FROM User');

    $user = $stmt->fetchAll();
    $stmt_cities = $dbh->prepare('SELECT * FROM City ORDER BY name ASC');
    $stmt_cities->execute();
    $cities = $stmt_cities->fetchAll();

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

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="widt=device-width, initial-scale=1.0">
<title>EasyWin Home</title>
<link rel="stylesheet" href="styleAccommodation.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

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

    <div class="wrapper">
        <form action="submit_home.php" method="POST" enctype="multipart/form-data">
            <h1>Add Accommodation</h1>

                <div class="input-box">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Enter address" required>
                    <?php if (isset($error_message)) { ?>
                    <p class="error-message"><?php echo $error_message ?></p>  
                    <?php } ?>
                </div>

                <div class="input-box">
                    <label for="city">City:</label>
                    <select id="city" name="city" required>
                        <?php foreach ($cities as $city) : ?>
                            <option value="<?php echo $city['name']; ?>"><?php echo $city['name']; ?></option>
                            <!-- <?php print_r($city); ?> -->
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-box">
                    <label for="capacity">Capacity:</label>
                    <input type="number" id="capacity" name="capacity" required>
                </div>

                <div class="image-container">
                    <label for="profile_pic">Upload a picture of your accomodation:</label>
                    <input type="file" id="accommod_pic" name="accommod_pic">
                </div>

            <div class="input-box">
                <button type="submit">Submit</button>
            </div>

        </form>
    </div>
</body>

</html>