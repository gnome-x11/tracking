<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Establishment List</title>
    <link rel="stylesheet" href="page-css/persons.css"> <!-- Your custom CSS -->
    <!-- Include Bootstrap if you're using it -->
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
						<b>Student Lists</b>
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
</body>
</html>

<script>
	$(document).ready(function () {
		$('table').dataTable()
	})
	$('#new_person').click(function () {
		uni_modal("New Person", "admin/manage_person.php", "mid-large")
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
