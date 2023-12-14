<?php
  session_start();

  $username = $_POST['username'];
  $password = $_POST['password'];
  function loginSuccess($username) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM User WHERE username = ? ');
    $stmt->execute([$username]);
    global $user;
    $user = $stmt->fetch();
    return $user;
  }

  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (loginSuccess($username) && password_verify($password, $user['password'])) {
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