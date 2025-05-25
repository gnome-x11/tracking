<?php
include 'db_connect.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <title>Establishment List</title>
    <link rel="stylesheet" href="page-css/visitor_list.css"> <!-- Your custom CSS -->
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
						<b>Visitors Logs</b>
						<span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
							<button class="btn btn-danger btn-sm mr-2" type="button" id="delete_visitors">
								<i class="fa fa-trash"></i> Delete Visitor Lists
							</button>
						<span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">

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
										<th class="text-center">No.</th>
										<th class="">Visitor ID</th>
										<th class="">Image</th>
										<th class="">Full Name</th>
										<th class="">Email</th>
										<th class="">Contact Number</th>
										<th class="">Purpose</th>
										<th class="">Date</th>
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

                                       " SELECT
                                            v.visitor_id,
                                            v.imagePath,
                                            v.full_name,
                                            v.email,
                                            v.contact_number,
                                            v.purpose,
                                            v.created_at
                                        FROM
                                            visitors v
                                        ORDER BY
                                            v.created_at DESC;
"

                                        );
									while ($row = $tracks->fetch_assoc()):
										?>
										<tr>

											<td class="text-center"><?php echo $i++ ?></td>

											<td class="">
												<p> <?php echo $row['visitor_id'] ?></p>
											</td>
											<td class="">
												<img src="<?php echo $row['imagePath'] ?>" >
											</td>
											<td class="">
												<p> <?php echo ucwords($row['full_name']) ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['email'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['contact_number'] ?></p>
											</td>
											<td class="">

												<p> <?php echo $row['purpose'] ?></p>
											</td>
											<td class="">
												<p> <?php echo $row['created_at'] ?></p>
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
		location.replace("index.php?page=visitor_list&from=" + $('[name="from"]').val() + "&to=" + $('[name="to"]').val())
	})

    function _conf(msg, func, params = []) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal').modal('show');
        $('#confirm_modal #confirm').attr('onclick', func + "(" + params.map(JSON.stringify).join(',') + ")");
    }

		$('#delete_visitors').click(function () {
		_conf ("Delete All Visitors?" , "confirm_delete_visitors");
		});

		function confirm_delete_visitors() {
		  delete_visitors();
		}

      function delete_visitors() {
        start_load()
          $.ajax({
            url: 'ajax.php?action=delete_visitors',
            method: 'POST',
            success: function (resp) {
              if (resp == 1) {
                alert_toast("Visitor list cleared successfully", "success")
                setTimeout(function () {
                  location.reload()
                }, 1500)
              }
            }

          })
      }
</script>
