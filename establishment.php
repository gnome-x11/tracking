<?php
include 'db_connect.php'; ?>
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
</style>
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
					<div class="card-header">
						<b>Estblishment List</b>
						<span class="float:right"><button class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
								type="button" id="new_establishment">
								<i class="fa fa-plus"></i> Add Establishment
							</button></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Establishment</th>

									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$types = $conn->query("SELECT * FROM establishments order by id asc");
								while ($row = $types->fetch_assoc()):
									?>
									<div>
										<td class="text-center"><?php echo $i++ ?></td>

										<td class="">
											<p> <b><?php echo ucwords($row['name']) ?></b></p>
										</td>


										<td class="text-center">
											<div class="d-flex flex-column gap-4 flex-md-row gap-3 justify-content-center">
												<button class="btn btn-sm btn-outline-primary edit_establishment"
													type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
												<button class="btn btn-sm btn-outline-danger delete_establishment"
													type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</div>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
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
	$('#new_establishment').click(function () {
		uni_modal("New Establishment", "manage_establishment.php")
	})

	$('.edit_establishment').click(function () {
		uni_modal("Edit Establishment", "manage_establishment.php?id=" + $(this).attr('data-id'))

	})
	$('.delete_establishment').click(function () {
		_conf("Are you sure to delete this Establishment?", "delete_establishment", [$(this).attr('data-id')])
	})

	function delete_establishment($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_establishment',
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
