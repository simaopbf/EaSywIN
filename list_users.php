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

    $query = "SELECT * FROM users WHERE 1 = 1";

    $params = array();

    if ($search_name != '') {
      $query = $query . ' AND name LIKE ?';
      $params[] = "%$search_name%";
    }

    $stmt = $dbh->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  $search_name = $_GET['search_name'];

  try {
    if (isset($search_name)) {
      $users = getUsersBySearch($search_name);
    } else {
      $users = getUsersBySearch('');
    }
  } catch (PDOException $e) {
    $error_msg = $e->getMessage();
  }
?>