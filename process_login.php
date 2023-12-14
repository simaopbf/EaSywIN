<?php
  session_start();

  // get username and password from HTTP parameters
  $username = $_POST['username'];
  $password = $_POST['password'];

  // check if username and password are correct
  function loginSuccess($username, $password) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM User WHERE username = ? AND password = ?');
    $stmt->execute(array($username, $password));
    return $stmt->fetch();
  }

  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (loginSuccess($username, $password)) {
      
      $_SESSION['username'] = $username;
      header('Location: homepage.php');

    } else {
      $_SESSION['msg'] = 'Invalid username or password!';
    }

  } catch (PDOException $e) {
    $_SESSION['msg'] = 'Error: ' . $e->getMessage();
  }
  

  include('login.php');
  die()
?>