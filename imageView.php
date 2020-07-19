<?php
    require_once "users/config.php";
    if(isset($_GET['image_id'])) {
        $query = "SELECT content_type, image FROM image_storage WHERE id=" . $_GET['image_id'];
		$result = mysqli_query($dbconn, $query) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($dbconn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["content_type"]);
        echo $row["image"];
	}
	mysqli_close($dbconn);
?>
