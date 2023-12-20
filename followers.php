<?php
session_start();

$userImage = "images/users/" . $_SESSION['username'] . ".jpg";
$defaultImage = "profile.png";

function checkIfFollowing($followerUsername, $followedUsername)
{
    global $dbh;

    $stmt = $dbh->prepare("SELECT * FROM Friend WHERE (user1_name = ? AND user2_name = ?)");
    $stmt->execute([$followerUsername, $followedUsername]);
    $existingConnection = $stmt->fetch();

    return $existingConnection !== false;
}

try {
    $dbh = new PDO('sqlite:sql/database.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $Username = $_SESSION['username'];

    $stmtFollowers = $dbh->prepare("SELECT user1_name FROM Friend WHERE user2_name = ?");
    $stmtFollowers->execute([$Username]);
    $followers = $stmtFollowers->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Followers</title>
    <link rel="stylesheet" href="findfriends.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="homepage.php">EasyInn</a>
            </div>
            <ul>
                <li><a href="#">Maps</a> </li>
                <li><a href="#howitworks">How it works</a> </li>
                <?php if (!isset($_SESSION['username'])) { ?>
                    <li><a class="backgroundcolor" href="login.php"> <i class='bx bxs-user'></i>  Login</a> </li>
                <?php } else { ?>
                    <li>
                        <a class="backgroundcolor" href="profile.php">
                            <img src="<?php echo $userImage; ?>">
                            <span><?php echo $_SESSION['username'] ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <div class="find_friends_container">
        <div class="find_friends_header">
            <div class="heading">Your followers</div>

            <div class="find_friends_body">
                <?php if (!empty($followers)) { ?>
                    <div class="user_row">
                        <?php foreach ($followers as $follower) { ?>
                            <form action="process-followerslist.php" method="post" class="user_form">
                                <div class="user_info">
                                    <?php
                                    $followerUsername = $follower['user1_name'];
                                    $userImage = "images/users/" . $followerUsername . ".jpg";
                                    $defaultImage = "profile.png";

                                    if (file_exists($userImage)) {
                                        $imageSource = $userImage;
                                    } else {
                                        $imageSource = $defaultImage;
                                    }
                                    ?>

                                    <input type="hidden" name="followed_username" value="<?php echo $followerUsername; ?>">
                                    <img src="<?php echo $imageSource; ?>">
                                    <span><?php echo $followerUsername; ?></span>

                                    <?php
                                    $isFollowing = checkIfFollowing($Username, $followerUsername);
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
