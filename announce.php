<?php
  session_start();

  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);

  /* $dbh = new PDO('sqlite:sql/products.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $dbh->prepare('SELECT * FROM Product WHERE cat_id=?');
  $stmt->execute(array($cat_id));
  $products = $stmt->fetchAll();

  $stmt = $dbh->prepare('SELECT name FROM Category WHERE id=?');
  $stmt->execute(array($cat_id));
  $category = $stmt->fetch(); */
try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->query('SELECT * FROM User /*WHERE image IS NOT NULL AND image <> ""*/');
    /*$stmt->execute(array($username));*/
    $user = $stmt->fetchAll();
    $stmt_accommods = $dbh->prepare('SELECT * FROM Accommodation ORDER BY id ASC');
    $stmt_accommods->execute();
    $accommodations = $stmt_accommods->fetchAll();
  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }
?>


<!DOCTYPE html>
<html lang="eng">

</head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="widt=device-width, initial-scale=1.0">
<title>EasyWin Home</title>
<link rel="stylesheet" href="styleAnnounce.css">
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

    <div class="center">
        <div class="wrapper">
            <form action="process_announce.php" method="POST">
                <h1>Announce</h1>
                <div class="input-box">
                    <label for="accommodation_id">Select Accommodation:</label>
                    <select id="accommodation_id" name="accommodation_id" required>
                        <?php foreach ($accommodations as $accommodation) : ?>
                            <option value="<?= $accommodation['id'] ?>"><?= $accommodation['id'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-box">
                    <label for="date_on">Start Date:</label>
                    <input type="date" id="date_on" name="date_on" required>
                </div>

                <div class="input-box">
                    <label for="date_off">End Date:</label>
                    <input type="date" id="date_off" name="date_off" required>
                </div>

                <div class="input-box">
                    <label for="descrip">Description:</label>
                    <textarea id="descrip" name="descrip" placeholder="Enter description"></textarea>
                </div>

                <div class="input-box-pp">
                    <label for="priv_publ">Public or Private:</label>
                    <select id="priv_publ" name="priv_publ" required>
                        <option value="1">Public</option>
                        <option value="0">Private</option>
                    </select>
                </div>

                <div class="input-box">
                    <button type="submit">Submit</button>
                </div>
           </form>
        </div>
    </div>


</body>

</html>

