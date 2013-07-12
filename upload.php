<?php
session_start ();

require_once('connections/dbconn.php');

$personID= $_POST['myName'];

if ($_POST["submit"]=='check'){
	header('Location: view.php?id='.$personID);
}

$allowedExts = array("gif", "jpeg", "jpg", "png");
$extension = end(explode(".", $_FILES["myFile"]["name"]));


$nameQuery = $db->query("SELECT name FROM users WHERE id = $personID");
$personName = $nameQuery->fetchColumn();
$personAmount = $_POST['myAmount'];

//if image attached
if ((($_FILES["myFile"]["type"] == "image/gif")
|| ($_FILES["myFile"]["type"] == "image/jpeg")
|| ($_FILES["myFile"]["type"] == "image/jpg")
|| ($_FILES["myFile"]["type"] == "image/pjpeg")
|| ($_FILES["myFile"]["type"] == "image/x-png")
|| ($_FILES["myFile"]["type"] == "image/png"))
&& in_array($extension, $allowedExts)) {
	
	if ($_FILES["myFile"]["error"] > 0) {
	    echo "Return Code: " . $_FILES["myFile"]["error"] . "<br>";
	}
	else {
		/*
	    echo "Upload: " . $_FILES["myFile"]["name"] . "<br>";
	    echo "Type: " . $_FILES["myFile"]["type"] . "<br>";
	    echo "Size: " . ($_FILES["myFile"]["size"] / 1024) . " kB<br>";
	    echo "Temp file: " . $_FILES["myFile"]["tmp_name"] . "<br>";
		*/
	    $uploadDir = "receipts/uploaded/";
	    if (file_exists($uploadDir . $_FILES["myFile"]["name"])) {
	      echo $_FILES["myFile"]["name"] . " already exists. ";
	    }
	    else {
	    	$date = new  DateTime();
	    	$savedURI = $uploadDir . date_format($date, 'Ymd-His-'). $personName .'.'. $extension;
		    move_uploaded_file($_FILES["myFile"]["tmp_name"], $savedURI);
		    //echo "Stored in: " . $savedURI;
		    $hasPhoto = true;
	    }
    }
	
}
//if no image attached
else {
	$savedURI='';
	$hasPhoto = false;
 	//echo 'no image';
}

    $query="INSERT INTO items (userid, amount, image) VALUES ('$personID', '$personAmount', '$savedURI')";
    $db->query($query);

?>

You, <?php echo $personName ?>, have submitted a claim for $<?php echo $personAmount ?>. 
<?php if($hasPhoto){
	?>
	Image successfully uploaded.
	<?php
} else{
	?>
	No image attached. Please email image to pipe@claims.minitheory.com
	<?php
}
?>

<a href="view.php?id=<?php echo $personID ?>">View my claims</a>