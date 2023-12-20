<?php
session_start();

$msg = $_SESSION['msg'];
unset($_SESSION['msg']);

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->query('SELECT * FROM User /*WHERE image IS NOT NULL AND image <> ""*/');
    /*$stmt->execute(array($username));*/
    $user = $stmt->fetchAll();
    $stmt_cities = $dbh->prepare('SELECT * FROM City ORDER BY name ASC');
    $stmt_cities->execute();
    $cities = $stmt_cities->fetchAll();
    
  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }
// Your HTML and form code for adding accommodations here
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
                        <li><a class="backgroundcolor" href="profile.php"><i class='bx bxs-user'></i>  Profile </a>  </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <div class="center">
        <div class="wrapper">
            <form action="submit_home.php" method="POST" enctype="multipart/form-data">
                 <h1>Add Accommodation</h1>
                 <div class="cols_container">
                    <div class = "left_col">
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
                    </div>

                    <div class = "right_col">
                        <div class="image-container">
                            <label for="accommod_pic">
                                <img id="accommod-image" src="accommod.png">
                            </label>
                            <input type="file" id="accommod_pic" name="accommod_pic" style="display: none;" accept="image/*" onchange="displayImage(this)">
                        </div>
                    </div>
                </div>

                <div class="input-box">
                    <button type="submit">Submit</button>
                </div>

                <script>
                    function displayImage(input) {
                        var file = input.files[0];
                        if (file) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                document.getElementById('accommod-image').src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                </script>
            </form>
        </div>
    </div>

</body>

</html>