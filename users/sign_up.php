<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Socialite</title>
    <link rel="stylesheet" href="../css/master.css">
  </head>

    <?php

    session_start();
    if (!empty($_SESSION["username"])) {
        header ("location: ../");
    }
    require_once "config.php";

    $username = $password = $confirm_password = "";
    $err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $query = "SELECT id FROM users WHERE username = ?";
        $st = mysqli_prepare($dbconn, $query);
        mysqli_stmt_bind_param($st, "s", $username);
        $username = trim($_POST["username"]);
        if (mysqli_stmt_execute($st)) {
            mysqli_stmt_store_result($st);
            if (mysqli_stmt_num_rows($st) == 1){
                $err = "Username exists";
            }
            else {
                  $username = trim($_POST["username"]);
            }
            mysqli_stmt_close($st);
        }

        $query = "SELECT id FROM users WHERE email = ?";
        $st = mysqli_prepare($dbconn, $query);
        mysqli_stmt_bind_param($st, "s", $email);
        $email = trim($_POST["email"]);
        if (mysqli_stmt_execute($st)) {
            mysqli_stmt_store_result($st);
            if (mysqli_stmt_num_rows($st) == 1){
                $err = "Email exists";
            }
            else {
                $email = trim($_POST["email"]);
            }
            mysqli_stmt_close($st);
        }

        if ($_POST["password"] != $_POST["confirm_password"]) {
            $err = "Passwords do not match";
        }
        else {
            $password = $_POST["password"];
        }

        if (empty($err)) {
            $query = "INSERT INTO users (username, encrypted_password, email) VALUES (?, ?, ?)";
            $st = mysqli_prepare($dbconn, $query);
            if (!$st) {
                die ("error" . mysqli_error($dbconn));
            }
            mysqli_stmt_bind_param($st, "sss", $pu, $pp, $pe);
            $pu = $username;
            $pp = password_hash($password, PASSWORD_DEFAULT);
            $pe = $email;
            if (mysqli_stmt_execute($st)) {
                header ("location: ../");
                $_SESSION ["username"] = $username;
            }
            else {
                echo "Some error";
            }
            mysqli_stmt_close($st);
        }
        else {
            echo $err;
        }
        mysqli_close($dbconn);
    }
    ?>
  <body>
    <nav>
      <div class="topnav">
        <a href="../">Socialite</a>
        <div class="topnav-right">
          <a href="sign_in.php" style="padding-left:0;">Login</a>
          <a href="sign_up.php" style="padding-left:0;">Register</a>
        </div>
      </div>
    </nav>
    <main>
      <div class="container">
        <div class="formholder">
          <div class="heading">
            &nbsp;Sign Up
          </div>
          <div class="form">
            <div class="container">
                <form method="post">
                  <div class="form-heading">Email</div>
                  <input type="email" autofocus="autofocus" autocomplete="email" name="email" class="input-field" required>
                  <div class="form-heading">User name</div>
                  <input type="text" autocomplete="username" name="username" class="input-field" required>
                  <div class="form-heading">Password</div>
                  <input type="password" autocomplete="current-password" name="password" class="input-field" required>
                  <div class="form-heading">Password Confirmation</div>
                  <input type="password" autocomplete="current-password" name="confirm_password" class="input-field" required>
                  <input type="submit" class="button" value="Sign up">
                </form>
            </div>
        </div>
      </div>
    </main>
  </body>
</html>
