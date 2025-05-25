<?php
include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Establishment List</title>
    <link rel="stylesheet" href="page-css/visitor_logs.css"> <!-- Your custom CSS -->
</head>
<body>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
						<b>Visitors Logs</b>
						<span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
							<button class="btn btn-danger btn-sm mr-2" type="button" id="clear_logs">
								<i class="fa fa-trash"></i> Delete All Logs
							</button>

						</span>
					</div>

					<div class="card-body">
						<div class="row form-group">
							<div class="col-md-4">
								<label for="" class="control-label">From</label>
								<input type="date" class="form-control" name="from"
									value="<?php echo isset($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : date('Y-m-d', strtotime(date('Y-m-1'))); ?>"
									required>
							</div>
							<div class="col-md-4">
								<label for="" class="control-label">To</label>
								<input type="date" class="form-control" name="to"
									value="<?php echo isset($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : date('Y-m-d', strtotime(date('Y-m-1') . " +1 month - 1 day")); ?>"
									required>
							</div>
							<div class="col-md-2">
								<label for="" class="control-label">&nbsp</label>
								<button class="btn btn-primary btn-block" id="filter" type="button">Filter</button>
							</div>
						</div>
						<hr>
						<div class="table-responsive">
							<table class="table table-bordered table-condensed table-hover">
								<colgroup>
									<col width="2%">
									<col width="10%">
									<col width="10%">
									<col width="15%">
									<col width="20%">
									<col width="15%">
									<col width="10%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="">Visitor ID</th>
										<th class="">Full Name</th>
										<th class="">Email</th>
										<th class="">Entered In</th>
										<th class="">Purposse</th>
										<th class="">Date</th>
										<th class="">Time - in</th>
										<th class="">Time - out</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$from = isset($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : date('Y-m-d', strtotime(date('Y-m-1')));
									$to = isset($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : date('Y-m-d', strtotime(date('Y-m-1') . " +1 month - 1 day"));
									$ewhere = '';
									if ($_SESSION['login_establishment_id'] > 0)
										$ewhere = " and t.establishment_id = '" . $_SESSION['login_establishment_id'] . "' ";
                                        $tracks = $conn->query(
                                        "SELECT
                                            v.visitor_id,
                                            v.full_name,
                                            v.contact_number,
                                            v.email,
                                            v.purpose,
                                            v.establishment_id AS v_establishment_id,
                                            v.created_at,
                                            v.token,
                                            v.token_expiry,
                                            l.log_id,
                                            e.name AS establishment_name, -- join and select the name
                                            l.time_in,
                                            l.time_out
                                        FROM visitors v
                                        INNER JOIN visitor_logs l ON v.visitor_id = l.visitor_id
                                        LEFT JOIN establishments e ON l.establishment_id = e.id
                                        WHERE DATE(v.created_at) BETWEEN '{$from}' AND '{$to}' $ewhere
                                        ORDER BY v.created_at DESC"

                                        );


									while ($row = $tracks->fetch_assoc()):
										?>
										<tr>

											<td class="text-center"><?php echo $i++ ?></td>

											<td class="">
												<p> <?php echo $row['visitor_id'] ?></p>
											</td>
											<td class="">
												<p> <?php echo ucwords($row['full_name']) ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['email'] ?></p>
											</td>
											<td class="">

												<p><?php echo htmlspecialchars($row['establishment_name'] ?? 'Unknown') ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['purpose'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['created_at'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['time_in'] ?></p>
											</td>
											<td class="">
												<p> <?php echo ucwords($row['time_out']) ?></p>
											</td>
										</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
</body>
</html>
<script>
	$('#filter').click(function () {
		location.replace("index.php?page=visitor&from=" + $('[name="from"]').val() + "&to=" + $('[name="to"]').val())
	})

function _conf(msg, func, params = []) {
    $('#confirm_modal .modal-body').html(msg);
    $('#confirm_modal').modal('show');
    $('#confirm_modal #confirm').attr('onclick', func + "(" + params.map(JSON.stringify).join(',') + ")");
}
	$('#clear_logs').click(function () {
    _conf("Are you sure you want to delete all visitor logs?", "confirm_clear_logs");
});

	function confirm_clear_logs() {
    clear_logs();
}

	function clear_logs() {
	  start_load()
			$.ajax({
			url: 'ajax.php?action=clear_logs',
			method: "POST",
			success: function (resp) {
			  if (resp == 1) {
					alert_toast("Visitor logs cleared succesffuly", "success")
					setTimeout(function() {
					location.reload()
					}, 1500)}
			    }
			})
	}
</script>
