<!DOCTYPE html>
<html lang="eng">

</head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EasyWin FindFriends</title>
<link rel="stylesheet" href="findfriends.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<head>

<body>
    <div class ="find_friends_container">
        <div class ="find_friends_header">
        <div class="heading">Find Friends</div>

        <form id="search" action="list_users.php">
            <div class="search">
                <i class='bx bx-search-alt-2'></i>
                <input type="text" name="search_name" placeholder="Enter a friend's name" value="<?php echo $search_name ?>">
            </div>
            <button>Search</button>
            <a href="list_users.php?cat=<?php echo $cat_id ?>">Clear</a>
        </form>

        <div class="list">

            <?php if ($error_msg == null) { ?>
            <?php foreach ($users as $row) { ?>
                <article>
                <h3><?php echo $_SESSION['username'] ?></h3>
                </article>
            <?php } ?>
            <?php } else {
            echo $error_msg;
            } ?>

        </div>

        </div>
        <div class ="find_friends_body">
            <div class="user_row">
                <div class="user_info">
                    <img src="images/users/cr7.jpg" alt="user">
                    <span>Cristiano Ronaldo</span>
                </div>
                <button class="action-follow">Follow</button>
            </div>
        </div>

    </div>
</body>
</html>