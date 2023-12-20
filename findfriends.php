<?php
  session_start();

  $userImage = "images/users/" . $row['username'] . ".jpg";
  $defaultImage = "profile.png";

    function checkIfFollowing($followedUsername){
    global $dbh;

    $followerUsername = $_SESSION['username'];

    $stmt = $dbh->prepare("SELECT * FROM Friend WHERE (user1_name = ? AND user2_name = ?)");
    $stmt->execute([$followerUsername, $followedUsername]);
    $existingConnection = $stmt->fetch();

    return $existingConnection !== false;
    }


    try {
        $dbh = new PDO('sqlite:sql/database.db');
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (file_exists($userImage)) {
            $imageSource = $userImage;
        } else {
            $imageSource = $defaultImage;
        }
        
    } catch (PDOException $e) {
        $error_msg = $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="eng">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EasyWin FindFriends</title>
<link rel="stylesheet" href="findfriends.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class ="find_friends_container">
        <div class ="find_friends_header">
            <div class="heading">Find Friends</div>

                <form id="search" action="list_users.php" class="search_container">
                    <div class="search">
                        <i class='bx bx-search-alt-2'></i>
                        <input type="text" name="search_name" placeholder="Friend's name" value="<?php echo isset($search_name) ? $search_name : ''; ?>">
                    </div>
                    <button type="submit">Search</button>
                    <a href="list_users.php">Clear</a>
                </form>

                <div class="find_friends_body">
                    <?php if (isset($users) && is_array($users) && count($users) > 0) { ?>
                        <div class = "user_row">
                            <?php foreach ($users as $row) { ?>
                                <form action="process-follow.php" method="post" class="user_form">
                                    <div class="user_info">
                                        <?php
                                            $userImage = "images/users/" . $row['username'] . ".jpg";
                                            $defaultImage = "profile.png";

                                            try {
                                                $dbh = new PDO('sqlite:sql/database.db');
                                                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                                                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                                if (file_exists($userImage)) {
                                                    $imageSource = $userImage;
                                                } else {
                                                    $imageSource = $defaultImage;
                                                }
                                                
                                            } catch (PDOException $e) {
                                                $error_msg = $e->getMessage();
                                            }
                                            ?>
                                            
                                            <input type="hidden" name="followed_username" value="<?php echo $row['username']; ?>">
                                            <img src="<?php echo $imageSource; ?>">
                                            <span><?php echo $row['username']; ?></span>   

                                            <?php
                                            $isFollowing = checkIfFollowing($row['username']);
                                            if ($isFollowing) {
                                                echo '<button type="submit" class="action-unfollow">Unfollow</button>';
                                            } else {
                                                echo '<button type="submit" class="action-follow">Follow</button>';
                                            }
                                        ?>
                                    </div>

                                </form> 
                            <?php } ?>
                        </div>
                    <?php } else {
                        echo "No users found!";
                    } ?>
                </div>
            </div>
    </div>
</body>
</html>