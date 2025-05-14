<?php include('db_connect.php'); ?>
<style type="text/css">
	body {
		font-family: 'Arial', sans-serif;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin: 20px 0;
		background: white;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
	}

	th {
		background: #f8f9fa;
		color: #333;
		font-weight: 600;
		padding: 12px 8px;
	}

	td {
		padding: 10px 8px;
		color: #444;
	}

	tr {
		border-bottom: 1px solid #e0e0e0;
	}

	tr:nth-child(even) {
		background-color: #f9f9f9;
	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}

	h3 {
		color: #2c3e50;
		margin: 25px 0 15px 0;
		padding-bottom: 10px;
		border-bottom: 2px solid #ecf0f1;
	}

	.header-title {
		font-size: 1.4em;
		letter-spacing: 0.5px;
	}
</style>
<?php
session_start();
$from = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime(date('Y-m-1')));
$to = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d', strtotime(date('Y-m-1') . " +1 month - 1 day"));
?>
<h3 class="text-center header-title"><b>Student Records</b><br>
	<small><?php echo date("M d,Y", strtotime($from)) . ' - ' . date("M d,Y", strtotime($to)) ?></small>
</h3>
<table>
	<colgroup>
		<col width="5%">
		<col width="15%">
		<col width="15%">
		<col width="20%">
		<col width="30%">
		<col width="15%">
	</colgroup>
	<thead>
		<tr>
			<th class="text-center">#</th>
			<th>Date</th>
			<th>Student ID</th>
			<th>Name</th>
			<th>Address</th>
			<th>Establishment</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 1;
		$ewhere = '';
		if ($_SESSION['login_establishment_id'] > 0)
			$ewhere = " and t.establishment_id = '" . $_SESSION['login_establishment_id'] . "' ";
		$tracks = $conn->query("SELECT t.*,concat(p.lastname,', ',p.firstname,' ',p.middlename) as name, concat(p.address,', ',p.street,', ',p.baranggay,', ',p.city,', ',p.state,', ',p.zip_code) as caddress,e.name as ename,p.student_id FROM person_tracks t inner join persons p on p.id = t.person_id inner join establishments e on e.id = t.establishment_id where date(t.date_created) between '$from' and '$to' $ewhere order by t.id desc");
		while ($row = $tracks->fetch_assoc()):
		?>
			<tr>
				<td class="text-center"><?php echo $i++ ?></td>
				<td><?php echo date("M d,Y h:i A", strtotime($row['date_created'])) ?></td>
				<td><?php echo $row['student_id'] ?></td>
				<td><?php echo ucwords($row['name']) ?></td>
				<td><?php echo $row['caddress'] ?></td>
				<td><?php echo ucwords($row['ename']) ?></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>