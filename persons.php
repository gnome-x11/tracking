<?php
include 'db_connect.php';
?>

<div class="container-fluid">
	<style>
		input[type=checkbox] {
			/* Double-sized Checkboxes */
			-ms-transform: scale(1.5);
			/* IE */
			-moz-transform: scale(1.5);
			/* FF */
			-webkit-transform: scale(1.5);
			/* Safari and Chrome */
			-o-transform: scale(1.0);
			/* Opera */
			transform: scale(1.5);
			padding: 10px;
		}


		.modal-dialog {
			margin-top: 10vh;
		}

		.modal-content {
			max-height: 90vh;
			overflow-y: auto;
		}

		/* General Styles */
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: white;
			color: #212529;

		}

		body.modal-open {
			overflow: hidden;
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
			background-color: rgb(130, 213, 174);
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

		th,
		td {
			padding: 0.75rem;
			border-bottom: 1px solidrgb(75, 238, 91);

		}

		th {
			background-color: rgb(65, 178, 125);
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
							<button class="btn btn-primary btn-sm mr-2" type="button" id="new_person">
								<i class="fa fa-plus"></i> New
							</button>
							<button class="btn btn-success btn-sm" type="submit" id="print_selected">
								<i class="fa fa-print"></i> Print
							</button>

						</span>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th class="text-center">
											<div class="form-check">
												<input class="form-check-input position-static" type="checkbox"
													id="check_all" aria-label="...">
											</div>
										</th>
										<th class="text-center">No.</th>
										<th class="photo">Photo</th>
										<th class="">Student ID</th>
										<th class="">Full Name</th>
										<th class="year">Year</th>
										<th class="standing">Standing</th>
										<th class="">College</th>
										<th class="">Course</th>
										<th class="">Address</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$types = $conn->query("SELECT *, concat(lastname, ', ', firstname, ' ', middlename) as name, concat(address, ', ', street, ', ', baranggay, ', ', city, ', ', state, ', ', zip_code) as caddress FROM persons ORDER BY name ASC");
									while ($row = $types->fetch_assoc()):
										?>
										<tr>
											<th class="text-center">
												<div class="form-check">
													<input class="form-check-input position-static input-md" type="checkbox"
														name="checked[]" value="<?php echo $row['id'] ?>">
												</div>
											</th>

											<td class="text-center"><?php echo $i++ ?></td>
											<td class="">
												<img src="uploads/<?php echo $row['photo'] ?>" width="60" height="60">
											</td>
											<td class=""><?php echo $row['student_id'] ?></td>

											<td class=""><?php echo ucwords($row['name']) ?></td>
											<td class=""><?php echo $row['year_level'] ?></td>
											<td class=""><?php echo $row['standing'] ?></td>
											<td class=""><?php echo $row['college'] ?></td>
											<td class=""><?php echo $row['course'] ?></td>
											<td class=""><?php echo $row['caddress'] ?></td>
											<td class="text-center">
												<div class="btn-group-vertical btn-group-sm d-block d-md-inline-block">
													<button class="btn btn-sm btn-outline-primary view_person mb-2 mb-md-0"
														type="button" data-id="<?php echo $row['id'] ?>">View</button>
													<button class="btn btn-sm btn-outline-primary edit_person mb-2 mb-md-0"
														type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
													<button class="btn btn-sm btn-outline-danger delete_person mb-2 mb-md-0"
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
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function () {
		$('table').dataTable()
	})
	$('#new_person').click(function () {
		uni_modal("New Person", "manage_person.php", "mid-large")
	})

	$('.edit_person').click(function () {
		uni_modal("Edit Person", "manage_person.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.view_person').click(function () {
		uni_modal("Person Details", "view_person.php?id=" + $(this).attr('data-id'), "large")

	})
	$('.delete_person').click(function () {
		_conf("Are you sure to delete this Person?", "delete_person", [$(this).attr('data-id')])
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
	$('#print_selected').click(function () {
		var checked = $('[name="checked[]"]:checked').length
		if (checked <= 0) {
			alert_toast("Check atleast one individual details row first.", "warning")
			return false;
		}
		var ids = [];
		$('[name="checked[]"]:checked').each(function () {
			ids.push($(this).val())
		})
		start_load()
		$.ajax({
			url: "print_persons.php",
			method: "POST",
			data: { ids: ids },
			success: function (resp) {
				if (resp) {
					var width = 900;
					var height = 600;
					var left = (screen.width / 2) - (width / 2);
					var top = (screen.height / 2) - (height / 2);
					var nw = window.open("", "_blank", `width=${width},height=${height},top=${top},left=${left}`);
					nw.document.write(resp)
					nw.document.close()
					setTimeout(function () {
						nw.close()
						end_load()
					}, 700)
				}
			}

		})
	})

	function delete_person($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_person',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'danger')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>
