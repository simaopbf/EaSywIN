<?php
session_start();

$username = $_POST['username'];
$email = $_POST['email'];
$tel = $_POST['phone'];
$password = $_POST['password'];
$password_confirmation = $_POST['password_confirmation'];

if ($password !== $password_confirmation) {
  $_SESSION['error_message']="Passwords must match";
  include('registration.php');
  die();
}

if (strlen($password) < 8) {
  $_SESSION['error_message'] = 'Password must have at least 8 characters.';
  include('registration.php');
  die();
}

function insertUser($username, $password, $email, $tel)
{
  global $dbh;
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $dbh->prepare('INSERT INTO User (username, password, email, phone_number) VALUES (?, ?, ?, ?)');
  $stmt->execute(array($username, $hashedPassword, $email, $tel));
}

function saveProfilePic($username) {
  move_uploaded_file($_FILES['profile_pic']['tmp_name'], "images/users/$username.jpg");
}

try {
  $dbh = new PDO('sqlite:sql/database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  insertUser($username, $password, $email, $tel);
  $_SESSION['msg'] = 'Registration successful!';
  header('Location: login.php');

  saveProfilePic($username);

} catch (PDOException $e) {
  $error_msg = $e->getMessage();

  if (strpos($error_msg, 'UNIQUE')) {
    $_SESSION['msg'] = 'Username already exists!';
  } else {
    $_SESSION['msg'] = "Registration failed! ($error_msg)";
  }
  include('registration.php');
  die();
}
?>