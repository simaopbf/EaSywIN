<?php
  session_start();
  $msg = $_SESSION['msg'];
  unset($_SESSION['msg']);

  $id=strval($_GET['id']);
  
  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM City INNER JOIN Climate ON City.meteorology =Climate.id_climate WHERE City.name=?');
    $stmt->execute([$id]);
    $city = $stmt->fetch();
    
    $stmt = $dbh->prepare('SELECT * FROM City INNER JOIN Point_of_interest ON City.city_id=Point_of_interest.city WHERE City.name=? AND Point_of_interest.point_category="Monument"');
    $stmt->execute([$id]);
    $monument = $stmt->fetchAll();
    
    $stmt = $dbh->prepare('SELECT * FROM City INNER JOIN Point_of_interest ON City.city_id=Point_of_interest.city WHERE City.name=? AND Point_of_interest.point_category="Place"');
    $stmt->execute([$id]);
    $place = $stmt->fetchAll();
    
    $stmt = $dbh->prepare('SELECT * FROM City INNER JOIN Point_of_interest ON City.city_id=Point_of_interest.city WHERE City.name=? AND Point_of_interest.point_category="Gastronomy"');
    $stmt->execute([$id]);
    $gastronomy = $stmt->fetchAll();
    
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
    <title>City</title>
    <link rel="stylesheet" href="style_city.css">

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
        <h1>City</h1>
        <h2>
            <?php if (isset($msg)) { ?>
                <p class="message"><?php echo $msg ?></p>  
            <?php } ?>
        </h2>

        <div class= "data">
                    <h4>Name: <?php echo $city['name'] ?></h4>
                    <h4>Weather: <?php echo $city['common_name'] ?></h4>
                        <?php
                            echo "<p>Cold Months: {$city['avg_cold']} Hot Months: {$city['avg_hot']} Precipitation: {$city['precipitation']}</p>";
                        ?>
                    <h3>Points of Interest </h3>
                    <h4>Monuments:
                        <?php 
                        foreach ($monument as $monu) {
                            echo $monu['point_name'] . '<br>';
                        }
                        ?>
                    </h4>

                    <h4>Places:
                        <?php 
                        foreach ($place as $pla) {
                            echo $pla['point_name'] . '<br>';
                        }
                        ?>
                    </h4>

                    <h4>Gastronomy:
                        <?php 
                        foreach ($gastronomy as $gastro) {
                            echo $gastro['point_name'] . '<br>';
                        }
                        ?>
                    </h4>
                    <h4>Average cost of living: <?php echo $city['average_cost_of_living'] ?> € </h4>
        </div>
        <div class="back">
                <p><a href="reservation.php">Back</a></p>
        </div>
    </div>
    
</body>

<html>