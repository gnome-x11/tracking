<?php
include 'db_connect.php'; ?>

<div class="container-fluid">

<style>
	/* General Styles */
	body {
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		background-color: #333;
		color: #212529;

	}

	.card {
		border: 1.2px solidrgb(169, 169, 169);
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
		background-color: white;
		padding: 15px;
		border-radius: 10px;
		margin-top: 50px;
	}

	.card-header {
		background-color:rgb(130, 213, 174);
		border-bottom: 1px solid #dee2e6;
		font-weight: 600;
		font-size: 1.1rem;
		padding: 1rem 1.25rem;
		border-radius: 10px;
	}

	.card-body {
		padding: 1.25rem;
	}

	label {
		font-weight: 500;
		margin-bottom: 0.5rem;
	}

	input[type="date"],
	.form-control,
	select {
		border-radius: 8px;
		border: 1px solid #ced4da;
		padding: 0.6rem 0.75rem;
	}

	.btn {
		border-radius: 8px;
		font-size: 0.95rem;
		padding: 0.5rem 1.2rem;
		transition: all 0.3s ease;
	}

	.btn-primary {
		background-color: #0d6efd;
		border-color: #0d6efd;
	}

	.btn-success {
		background-color: #198754;
		border-color: #198754;
	}

	.btn-outline-primary,
	.btn-outline-danger {
		border-width: 1px;
	}

	.btn:hover {
		opacity: 0.9;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 1rem;
	}

	th, td {
		padding: 0.75rem;
		border-bottom: 1px solidrgb(75, 238, 91);

	}

	th {
		background-color:rgb(65, 178, 125);
		font-weight: 600;
		text-align: left;
		color: white;

	}

	.table-responsive {

		overflow-x: auto;
	}

	@media (max-width: 768px) {
		.card-header .btn {
			margin-top: 10px;
			width: 100%;
		}

		.form-group .col-md-4,
		.form-group .col-md-2 {
			width: 100%;
			margin-bottom: 10px;
		}
	}
</style>

	<style>
		input[type=checkbox] {
			/* Double-sized Checkboxes */
			-ms-transform: scale(1.5);
			/* IE */
			-moz-transform: scale(1.5);
			/* FF */
			-webkit-transform: scale(1.5);
			/* Safari and Chrome */
			-o-transform: scale(1.5);
			/* Opera */
			transform: scale(1.5);
			padding: 10px;

			@media (max-width: 768px) {
				.card-header .btn {
					margin-top: 10px;
					width: 100%;
				}

				.form-group .col-md-4,
				.form-group .col-md-2 {
					width: 100%;
					margin-bottom: 10px;
				}

				.card-body {
					overflow-x: auto;
				}

				table {
					min-width: 800px;
				}
			}

		}


		.modal-dialog {
			margin-top: 10vh;
		}


		.modal-content {
			max-height: 80vh;

			overflow-y: auto;

		}

		.toast {
			display: none;
			min-width: 20vw
		}

		.toast.show {
			display: block;
			opacity: 1;
			position: fixed;
			z-index: 99999999;
			margin: 20px;
			right: 0;
			top: 3.5rem;
		}
		.back-to-top {
  position: fixed;
  display: none;
  right: 15px;
  bottom: 15px;
  z-index: 99999;
}

.back-to-top i {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  width: 40px;
  height: 40px;
  border-radius: 50px;
  background: #1977cc;
  color: #fff;
  transition: all 0.4s;
}

.back-to-top i:hover {
  background: #1c84e3;
  color: #fff;
}

	</style>
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
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}

	img {
		max-width: 100px;
		max-height: 150px;
	}
</style>
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
