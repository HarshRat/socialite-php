<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>New Post</title>
        <link rel="stylesheet" href="css/master.css">
    </head>

    <?php
        session_start();
        if (empty($_SESSION["username"])) {
            header("location: users/sign_in.php");
        }
        if (count($_FILES) > 0) {
            $uid = 0;
            require_once "users/config.php";
            $query = "SELECT id FROM users WHERE username = ?";
            $st = mysqli_prepare($dbconn, $query);
            mysqli_stmt_bind_param($st, "s", $uname);
            $uname = $_SESSION["username"];
            if (mysqli_stmt_execute($st)) {
                mysqli_stmt_store_result($st);
                if (mysqli_stmt_num_rows($st) == 1) {
                    mysqli_stmt_bind_result ($st, $uid);
                    mysqli_stmt_fetch($st);
                }
            }
            mysqli_stmt_close($st);
            if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                $img_data = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                $filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
                $imageProperties = getimageSize($_FILES['image']['tmp_name']);
                $query = "INSERT INTO image_storage(image_name,image, content_type) VALUES('{$filename}', '{$img_data}', '{$imageProperties['mime']}')";
                $current_id = mysqli_query($dbconn, $query) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($dbconn));
                $query = "INSERT INTO posts (caption, user_id) VALUES (?, ?)";
                $st = mysqli_prepare($dbconn, $query);
                if (!$st) {
                    die (mysqli_error($dbconn));
                }
                mysqli_stmt_bind_param($st, "ss", $cap, $uid);
                $cap = trim($_POST["caption"]);
                $uid = $uid;
                if (isset($current_id) && mysqli_stmt_execute($st)) {
                    header("Location: ./");
                }
            }
            else {
                echo "NO";
            }
            mysqli_close($dbconn);
        }
    ?>

    <body>
        <nav>
          <div class="topnav">
            <a href="/">Socialite</a>
            <div class="topnav-right">
              <a href="./" style="padding-left:0;">Home</a>
              <a href="logout.php" style="padding-left:0;">Log out</a>
            </div>
          </div>
        </nav>
        <div class="container">
            <div class="formholder">
                <div class="heading">
                    New Post
                </div>
                <div class="form">
                    <div class="container">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-heading">Image</div>
                            <input type="file" name="image" class="input-field-button" required>
                            <div class="form-heading">Caption</div>
                            <input type="text" name="caption" class="input-field" required>
                            <input type="submit" class="button" value="Upload">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
