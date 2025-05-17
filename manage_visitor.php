<style>
	.form-header-title {
		font-weight: bold;
		font-size: 16px;
		margin-bottom: 20px;
		color: green;
	}
	.control-label {
		font-weight: bold;
		color: grey;
	}
	.modal-dialog {
    margin-top: 20vh;
}

.modal-content {
    max-height: 90vh;
    overflow-y: auto;
}
</style>

<?php
session_start(); // Make sure the session is started

include 'db_connect.php';


if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM visitors WHERE id = " . $_GET['id']);
	foreach ($qry->fetch_array() as $k => $val) {
		$$k = $val;
	}
}
?>

<div class="container-fluid">
	<form action="" id="manage-visitor" enctype="multipart/form-data">
		<input type="hidden" name="visitor_id" value="<?php echo isset($visitor_id) ? $visitor_id : '' ?>">
		<div id="msg"></div>
		<div class="row form-group">
			<div class="form-header col-md-12">
				<h5 class="form-header-title">Visitor Information</h5>
			</div>

			<div class="col-md-4">
				<label class="control-label">Full Name</label>
				<input type="text" class="form-control" name="full_name" value="<?php echo isset($full_name) ? $full_name : '' ?>" required>
			</div>

			<div class="col-md-4">
				<label class="control-label">Contact Number</label>
				<input type="text" class="form-control" name="contact_number" value="<?php echo isset($contact_number) ? $contact_number : '' ?>" required>
			</div>

			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>" required>
			</div>

			<div class="col-md-12">
				<label class="control-label">Purpose</label>
				<textarea name="purpose" class="form-control" rows="3" required><?php echo isset($purpose) ? $purpose : '' ?></textarea>
			</div>
			<input type="hidden" name="establishment_id"
				value="<?php echo isset($establishment_id) ? $establishment_id : $_SESSION['login_establishment_id'] ?>">
			<input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>">

		</div>
	</form>
</div>

<script>
	$('#manage-visitor').submit(function (e) {
		e.preventDefault();
		start_load();
		$('#msg').html('');

		var formData = new FormData($(this)[0]);
		$.ajax({
			url: 'ajax.php?action=save_visitor',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			success: function (resp) {
				console.log("Response: ", resp);

				try {
					resp = JSON.parse(resp); // convert JSON string to JS object
				} catch (e) {
					console.error("Invalid JSON response:", resp);
					$('#msg').html("<div class='alert alert-danger'>Invalid server response.</div>");
					end_load();
					return;
				}

				if (resp.status == 1) {
					alert_toast("Visitor data successfully saved", 'success');
					setTimeout(function () {
						location.reload();
					}, 1500);
				} else {
					$('#msg').html("<div class='alert alert-danger'>Error saving data. </div>");
					end_load();
				}
			}
		});
	});
</script>
