<?php
session_start ();
require_once('connections/dbconn.php');

$allowedExts = array("gif", "jpeg", "jpg", "png");
$extension = end(explode(".", $_FILES["myFile"]["name"]));


if ((($_FILES["myFile"]["type"] == "image/gif")
|| ($_FILES["myFile"]["type"] == "image/jpeg")
|| ($_FILES["myFile"]["type"] == "image/jpg")
|| ($_FILES["myFile"]["type"] == "image/pjpeg")
|| ($_FILES["myFile"]["type"] == "image/x-png")
|| ($_FILES["myFile"]["type"] == "image/png"))
&& in_array($extension, $allowedExts)) {
	
	$personID= $_POST['myName'];
	$nameQuery = $db->query("SELECT name FROM users WHERE id = $personID");
	$personName = $nameQuery->fetchColumn();
	$personAmount = $_POST['myAmount'];
	$imageName = $personName . $_FILES["myFile"]["name"];
	$query="INSERT INTO items (userid, amount, image) VALUES ('$personName', '$personAmount', '$imageName')";

	
	if ($_FILES["myFile"]["error"] > 0) {
	    echo "Return Code: " . $_FILES["myFile"]["error"] . "<br>";
	}
	else {
	    echo "Upload: " . $_FILES["myFile"]["name"] . "<br>";
	    echo "Type: " . $_FILES["myFile"]["type"] . "<br>";
	    echo "Size: " . ($_FILES["myFile"]["size"] / 1024) . " kB<br>";
	    echo "Temp file: " . $_FILES["myFile"]["tmp_name"] . "<br>";

	    $uploadDir = "receipts/uploaded/";
	    if (file_exists($uploadDir . $_FILES["myFile"]["name"])) {
	      echo $_FILES["myFile"]["name"] . " already exists. ";
	    }
	    else {
	    	$date = new  DateTime();
		    move_uploaded_file($_FILES["myFile"]["tmp_name"], $uploadDir . date_format($date, 'Ymd-His-'). $personName .'.'. $extension);
		    echo "Stored in: " . $uploadDir . date_format($date, 'Ymd-His-'). $personName .'.'. $extension;
		      

		    mysql_query($query,$claims);
	    }
    }
}
else {
  echo "Invalid file";
}

?>