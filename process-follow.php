<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $followed_username = isset($_POST['followed_username']) ? $_POST['followed_username'] : '';

    if ($followed_username !== '') {
        try {
            $dbh = new PDO('sqlite:sql/database.db');
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $follower_username = $_SESSION['username'];
            // Retrieve followed 
            $stmt_check_user = $dbh->prepare("SELECT * FROM User WHERE username = ?");
            $stmt_check_user->execute([$followed_username]);
            $followed_user = $stmt_check_user->fetch();

            if ($followed_user) {
                $stmt_check_connection = $dbh->prepare("SELECT * FROM Friend WHERE (user1_name = ? AND user2_name = ?) OR (user1_name = ? AND user2_name = ?)");
                $stmt_check_connection->execute([$follower_username, $followed_username, $followed_username, $follower_username]);
                $existing_connection = $stmt_check_connection->fetch();

                if (!$existing_connection) {
                    $stmt_insert_connection = $dbh->prepare("INSERT INTO Friend (user1_name, user2_name) VALUES (?, ?)");
                    $stmt_insert_connection->execute([$follower_username, $followed_username]);
                }
                else{
                    $stmt_remove_connection = $dbh->prepare("DELETE FROM Friend WHERE (user1_name = ? AND user2_name = ?)");
                    $stmt_remove_connection->execute([$follower_username, $followed_username]);
        
                }

                header('Location: list_users.php');
                exit();
            } else {
            }
        } catch (PDOException $e) {
            $error_msg = $e->getMessage();
        }
    }
}


header('Location: list_users.php');
exit();
