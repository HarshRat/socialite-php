<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>HOME</title>
        <link rel="stylesheet" href="css/master.css">
    </head>
    <body>
        <?php
            session_start();
            if (empty($_SESSION["username"])) {
                header("location: users/sign_in.php");
            }

            require_once "users/config.php";
            $query = "SELECT id FROM image_storage ORDER BY id DESC";
            $result = mysqli_query($dbconn, $query);
            if (!$result) {
                echo mysqli_error($dbconn);
            }
        ?>
        <nav>
          <div class="topnav">
            <a href="./">Socialite</a>
            <div class="topnav-right">
              <a href="new.php" style="padding-left:0;">New post</a>
              <a href="logout.php" style="padding-left:0;">Log out</a>
            </div>
          </div>
        </nav>
        <br>
        <div class="container">
                <?php
                    while($row = mysqli_fetch_array($result)) {
                    ?>
                        <div class="formholder">
                            <?php
                                $query = "SELECT user_id, caption FROM posts WHERE id=" . $row["id"];
                                $res = mysqli_query ($dbconn, $query) or die ("Error: Database Error");
                                $arr = mysqli_fetch_array($res);
                                $query = "SELECT username, id FROM users WHERE id=" . $arr["user_id"];
                                $res = mysqli_query ($dbconn, $query) or die ("Error: Database Error");
                                $arr2 = mysqli_fetch_array($res);
                            ?>
                            <span class="username"> <?php echo $arr2["username"]; ?></span>
                            <br><br>
                            <img class = "post-image" src="imageView.php?image_id=<?php echo $row["id"]; ?>" /><br/><br>
                            <div class = "caption"> <?php echo $arr["caption"]; ?> </div>
                        </div>
                        <br>

                    <?php
                    }
                    mysqli_close($dbconn);
                ?>
        </div>
    </body>
</html>
