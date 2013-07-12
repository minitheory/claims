<?php
session_start ();
require_once('connections/dbconn.php');
$userID = $_GET['id'];

$myName = $db->query("SELECT name FROM users WHERE id=$userID");
$myName = $myName->fetchColumn();

$myClaims = $db->prepare("SELECT * FROM items WHERE userid = $userID ORDER BY timestamp DESC");
$myClaims->execute();

?>
Hi <?php echo $myName  ?>, your claims:
<table>
	<thead>
		<tr>
			<th>Amount</th>
			<th>Date/Time</th>
			<th>Receipt</th>
			<th>Action</th>
		</tr>
	</thead>
	<?php foreach($myClaims as $row){ ?>
	<tr>
		<td><?php echo $row['amount'] ?></td>
		<td><?php echo $row['timestamp'] ?></td>
		<td>
			<?php if ($row['image']){?>
			<a href="<?php echo $row['image'] ?>">view receipt</a>
			<?php } ?>
		</td>
		<td>action</td>
	</tr>
	<?php } ?>
</table>