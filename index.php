<?php 
// include info for db connection
	require_once('connections/dbconn.php');
	
	if (!isset($_SESSION)) {
	  session_start();
	}

	$users = $db->query('SELECT * FROM users');

?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
		<meta name="description" content="">
        <meta name="viewport" content="width=device-width">
		<title></title>

		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>
	<body>
		<form method="post" action="upload.php" enctype="multipart/form-data">
			<div class="control">
				<select name="myName">
					<option value="0" disabled selected>Find Thyself</option>
					<?php foreach($db->query('SELECT * FROM users') as $row) { ?>
					<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="control">
				$<input type="text" placeholder="Claim amount" name="myAmount"/>
			</div>
			<div class="control">
				<input type="file" capture="camera" accept="image/*" name="myFile" />
			</div>
			<div class="control">
				<button type="submit">Submit Claim</button>
			</div>
		</form>
	</body>

</html>