<?php
  session_start();

  try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } 
  catch (PDOException $e) {
    die($e->getMessage());
  }

  function getUsersBySearch($search_name) {
    global $dbh;

    $query = "SELECT * FROM User WHERE 1";
    $params = array();

    if ($search_name != '') {
      $query = $query . ' AND username LIKE ?';
      $params[] = "%$search_name%";
    }

    $stmt = $dbh->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';

  try {
    $users = getUsersBySearch($search_name);
  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }

  include('findfriends.php')
  
?>