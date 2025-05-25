<?php
    include 'db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Establishment List</title>
    <link rel="stylesheet" href="page-css/establishment.css">
</head>
<body>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
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
                                    while ($row = $types->fetch_assoc()): ?>
									<div>
										<td class="text-center"><?php echo $i++; ?></td>

										<td class="">
											<p> <b><?php echo ucwords($row['name']); ?></b></p>
										</td>
										<td class="text-center">
											<div class="d-flex flex-column gap-4 flex-md-row gap-3 justify-content-center">
												<button class="btn btn-sm btn-outline-primary edit_establishment"
													type="button" data-id="<?php echo $row['id']; ?>">Edit</button>
												<button class="btn btn-sm btn-outline-danger delete_establishment"
													type="button" data-id="<?php echo $row['id']; ?>">Delete</button>
										</td>
									</div>
									</tr>
								<?php endwhile;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
<script src="page_js/establishment.js"></script>
</html>
