<?php
session_start ();
require_once('connections/dbconn.php');

$all = $db->query("SELECT * FROM items ORDER BY timestamp DESC");

?>

<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Amount</th>
			<th>Date/Time</th>
			<th>Receipt</th>
			<th>Action</th>
		</tr>
	</thead>
	<?php foreach($all as $row){ 
		$query = $db->prepare("SELECT name FROM users WHERE id = :id");
		$query->execute(array(':id'=>$row['userid']));
		$name = $query->fetchColumn();

	?>
	<tr>
		<td><?php echo $name ?></td>
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