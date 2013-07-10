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

$personName = $_POST['myName'];
$personPrice = $_POST['myPrice'];
$imageName = $personName . $_FILES["myFile"]["name"];

$sql="INSERT INTO user (name, price, image) VALUES ('$personName', '$personPrice', '$imageName')";

if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    } else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("upload/" . $_FILES["file"]["name"])) {
      echo $_FILES["file"]["name"] . " already exists. ";
      } else {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "upload/" . $personName . $_FILES["file"]["name"]);
      echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
      

      mysql_query($sql,$claims);
      }
    }
  } else {
  echo "Invalid file";
  }

?>