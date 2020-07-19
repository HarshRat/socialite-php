<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'socialite');

$dbconn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($dbconn === false){
    die("Could not connect to SQL. " . mysqli_connect_error());
}
?>
