<?php
include 'db_connect.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <title>Establishment List</title>
    <link rel="stylesheet" href="page-css/records.css">
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
						<b>Student Monitoring List</b>
						<span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">

							<button class="btn btn-success btn-sm" type="button" id="print">
								<i class="fa fa-print"></i> Print
							</button>
							<button class="btn btn-danger btn-sm" type="button" id="clear_records">
								<i class="fa fa-trash"></i> Clear
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
										<th class="">Photo</th>										<th class="">Name</th>
										<th class="">College</th>
										<th class="">Course</th>
										<th class="">Year</th>
										<th class="">Standing</th>
										<th class="">Establishment</th>
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
												p.photo,

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
											<img style="height: auto; width: 60px;" src="uploads/<?php echo $row['photo']?>">
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

	function _conf(msg, func, params = []) {
              $('#confirm_modal .modal-body').html(msg);
              $('#confirm_modal').modal('show');
              $('#confirm_modal #confirm').attr('onclick', func + "(" + params.map(JSON.stringify).join(',') + ")");
          }
             	$('#clear_records').click(function () {
                        _conf("Are you sure you want to delete all records?", "confirm_clear_records");
                    });

             	function confirm_clear_records() {
                                      clear_records();
                                  }

             	function clear_records() {
             	  start_load()
             			$.ajax({
             			url: 'ajax.php?action=clear_records',
             			method: "POST",
             			success: function (resp) {
             			  if (resp == 1) {
             					alert_toast(" Cleared succesffuly", "success")
             					setTimeout(function() {
             					location.reload()
             					}, 1500)}
             			    }
             			})
             	}
    </script>
