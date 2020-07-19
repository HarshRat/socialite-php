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
    $email = $password = $username = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $query = "SELECT username, encrypted_password FROM users WHERE email = ?";
        $st = mysqli_prepare($dbconn, $query);
        mysqli_stmt_bind_param($st, "s", $email);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        if (mysqli_stmt_execute($st)) {
            mysqli_stmt_store_result($st);
            if (mysqli_stmt_num_rows($st) == 1) {
                mysqli_stmt_bind_result ($st, $username, $encrypted_password);
                if(mysqli_stmt_fetch($st)) {
                    if(password_verify($password, $encrypted_password)) {
                        session_start();
                        $_SESSION["username"] = $username;
                        header("location: ../");
                    }
                    else{
                        echo "Invalid credentials.";
                    }
                }
            }
            else {
                echo "Invalid credentials.";
            }
        }
        mysqli_stmt_close($st);
    }
    mysqli_close($dbconn);
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
            &nbsp;Log-in
          </div>
          <div class="form">
            <div class="container">
              <form method="post">
                  <div class="form-heading">Email</div>
                  <input type="email" autofocus="autofocus" autocomplete="email" name="email" class="input-field" required>
                  <div class="form-heading">Password</div>
                  <input type="password" autocomplete="current-password" name="password" class="input-field" required>
                  <input type="submit" class="button" value="Log in">
              </form>
            </div>
        </div>
      </div>
    </main>
  </body>
</html>
