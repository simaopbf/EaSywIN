<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $followed_username = isset($_POST['followed_username']) ? $_POST['followed_username'] : '';

    if ($followed_username !== '') {
        try {
            $dbh = new PDO('sqlite:sql/database.db');
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtenha o ID do usuário logado
            $follower_username = $_SESSION['username'];

            // Verifique se o usuário que está sendo seguido existe
            $stmt_check_user = $dbh->prepare("SELECT * FROM User WHERE username = ?");
            $stmt_check_user->execute([$followed_username]);
            $followed_user = $stmt_check_user->fetch();

            if ($followed_user) {
                // Verifique se a conexão já existe
                $stmt_check_connection = $dbh->prepare("SELECT * FROM Friend WHERE (user1_name = ? AND user2_name = ?) OR (user1_name = ? AND user2_name = ?)");
                $stmt_check_connection->execute([$follower_username, $followed_username, $followed_username, $follower_username]);
                $existing_connection = $stmt_check_connection->fetch();

                if (!$existing_connection) {
                    // Crie uma nova conexão de amizade
                    $stmt_insert_connection = $dbh->prepare("INSERT INTO Friend (user1_name, user2_name) VALUES (?, ?)");
                    $stmt_insert_connection->execute([$follower_username, $followed_username]);
                }

                // Redirecione de volta para a página de amigos após o follow
                header('Location: findfriends.php');
                exit();
            } else {
                // Usuário não encontrado, lidar com o erro
            }
        } catch (PDOException $e) {
            $error_msg = $e->getMessage();
            // Lidar com erros, se necessário
        }
    }
}

// Redirecione de volta para a página de amigos se a requisição não for POST ou se algo der errado
header('Location: findfriends.php');
exit();
