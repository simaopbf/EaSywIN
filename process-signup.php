<?php

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    $error_message="Passwords must match";
    include('registration.php');
    exit();
}

$password_hash = password_hash($S_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

print_r($_POST);
var_dump($password_hash);

?>