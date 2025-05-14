<?php 
include 'db_connect.php'; ?>

<div class="container-fluid">

<style>
	/* General Styles */
	body {
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		background-color: white;
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
						<b>Monitoring List</b>
						<span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
							<button class="btn btn-primary btn-sm mr-2" type="button" id="new_records">
								<i class="fa fa-plus"></i> New
							</button>
							<button class="btn btn-success btn-sm" type="button" id="print">
								<i class="fa fa-print"></i> Print
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
										<th class="">Date</th>
										<th class="">Student Number</th>
										<th class="">Name</th>
										<th class="">College</th>
										<th class="">Course</th>
										<th class="">Year</th>
										<th class="">Standing</th>
										<th class="">Establishment</th>
										<th class="text-center">Action</th>
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
												t.*,concat(p.lastname,', ',p.firstname,' ',p.middlename) as name, 
												p.college,
												p.course,
												p.year_level,
												p.standing,

												e.name as ename,p.student_id 
												FROM person_tracks t 
												inner join persons p on p.id = t.person_id 
												inner join establishments e on e.id = t.establishment_id 
												where date(t.date_created) between '$from' and '$to' $ewhere order by t.id desc"
									);

									while ($row = $tracks->fetch_assoc()):
										?>
										<tr>

											<td class="text-center"><?php echo $i++ ?></td>
											<td class="">
												<p> <?php echo date("M d,Y h:i A", strtotime($row['date_created'])) ?></b>
												</p>
											</td>
											<td class="">
												<p> <?php echo $row['student_id'] ?></p>
											</td>
											<td class="">
												<p> <?php echo ucwords($row['name']) ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['college'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['course'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['year_level'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['standing'] ?></p>
											</td>
											<td class="">
												<p> <?php echo ucwords($row['ename']) ?></p>
											</td>

											


											<td class="text-center">
												<div class="btn-group-vertical btn-group-sm d-block d-md-inline-block">
													<button class="btn btn-sm btn-outline-primary edit_records mb-2 mb-md-0"
														type="button" data-id="<?php echo $row['id'] ?>">Edit</button><br>
													<button class="btn btn-sm btn-outline-danger delete_records"
														type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
												</div>
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
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function () {
		$('table').dataTable()
	})
	$('#new_records').click(function () {
		uni_modal("New Record", "manage_records.php")
	})

	$('.edit_records').click(function () {
		uni_modal("Edit Record", "manage_records.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.delete_records').click(function () {
		_conf("Are you sure to delete this Person?", "delete_records", [$(this).attr('data-id')])
	})
	$('#check_all').click(function () {
		if ($(this).prop('checked') == true)
			$('[name="checked[]"]').prop('checked', true)
		else
			$('[name="checked[]"]').prop('checked', false)
	})
	$('[name="checked[]"]').click(function () {
		var count = $('[name="checked[]"]').length
		var checked = $('[name="checked[]"]:checked').length
		if (count == checked)
			$('#check_all').prop('checked', true)
		else
			$('#check_all').prop('checked', false)
	})
	$('#print').click(function () {
		start_load()
		$.ajax({
			url: "print_records.php",
			method: "POST",
			data: { from: '<?php echo $from ?>', to: "<?php echo $to ?>" },
			success: function (resp) {
				if (resp) {
					var nw = window.open("", "_blank", "height=600,width=900")
					nw.document.write(resp)
					nw.document.close()
					nw.print()
					setTimeout(function () {
						nw.close()
						end_load()
					}, 700)
				}
			}
		})
	})
	$('#filter').click(function () {
		location.replace("index.php?page=records&from=" + $('[name="from"]').val() + "&to=" + $('[name="to"]').val())
	})

	function delete_records($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_records',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>