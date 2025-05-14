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
</style>

<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM persons where id= " . $_GET['id']);
	foreach ($qry->fetch_array() as $k => $val) {
		$$k = $val;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-person" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg"></div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Photo</label>
				<input type="file" name="photo" class="form-control">
				<?php if (!empty($photo)): ?>
					<img src="uploads/<?php echo $photo ?>" width="100" class="mt-2">
				<?php endif; ?>
			</div>
		</div>

		<hr>

		<div class="row form-group">
			<div class="form-header col-md-12">
				<h5 class="form-header-title">Student Information</h5>
			</div>
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"
					value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"
					value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename"
					value="<?php echo isset($middlename) ? $middlename : '' ?>" required>
			</div>
		</div>

		<!-- Address Info -->
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Sub./Building/Block/Lot</label>
				<textarea name="address" class="form-control"
					required><?php echo isset($address) ? $address : '' ?></textarea>
			</div>
			<div class="col-md-4">
				<label class="control-label">Street</label>
				<textarea name="street" class="form-control"
					required><?php echo isset($street) ? $street : '' ?></textarea>
			</div>
			<div class="col-md-4">
				<label class="control-label">Baranggay</label>
				<textarea name="baranggay" class="form-control"
					required><?php echo isset($baranggay) ? $baranggay : '' ?></textarea>
			</div>
		</div>

		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">City</label>
				<textarea name="city" class="form-control" required><?php echo isset($city) ? $city : '' ?></textarea>
			</div>
			<div class="col-md-4">
				<label class="control-label">State/Province</label>
				<textarea name="state" class="form-control"
					required><?php echo isset($state) ? $state : '' ?></textarea>
			</div>
			<div class="col-md-4">
				<label class="control-label">Zip Code</label>
				<textarea name="zip_code" class="form-control"
					required><?php echo isset($zip_code) ? $zip_code : '' ?></textarea>
			</div>
		</div>
		<hr>
		<div class="row form-group">
			<div class="form-header col-md-12">
				<h5 class="form-header-title">Student ID Information</h5>
			</div>
			<div class="col-md-4">
				<label class="control-label">Student ID</label>
				<input type="text" class="form-control" name="student_id"
					value="<?php echo isset($student_id) ? $student_id : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Year Level</label>
				<select name="year_level" class="form-control" required>
					<?php for ($i = 1; $i <= 5; $i++): ?>
						<option value="<?php echo $i ?>" <?php echo (isset($year_level) && $year_level == $i) ? 'selected' : '' ?>><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
			</div>

			<div class="col-md-4">
				<label class="control-label">Standing</label>
				<select name="standing" class="form-control" required>
					<option value="REGULAR" <?php echo (isset($standing) && $standing == 'REGULAR') ? 'selected' : '' ?>>
						REGULAR</option>
					<option value="IRREGULAR" <?php echo (isset($standing) && $standing == 'IRREGULAR') ? 'selected' : '' ?>>IRREGULAR</option>
				</select>
			</div>

			<div class="col-md-4">
				<label class="control-label">College</label>
				<select name="college" class="form-control" id="collegeSelect" required>
					<option value="">Select College</option>
					<option value="CAS" <?= (isset($college) && $college == 'CAS') ? 'selected' : '' ?>>College of Arts and
						Sciences (CAS)</option>
					<option value="CBA" <?= (isset($college) && $college == 'CBA') ? 'selected' : '' ?>>College of Business
						Administration (CBA)</option>
					<option value="COA" <?= (isset($college) && $college == 'COA') ? 'selected' : '' ?>>College of
						Accountancy</option>
					<option value="CCJ" <?= (isset($college) && $college == 'CCJ') ? 'selected' : '' ?>>College of Criminal
						Justice (CCJ)</option>
					<option value="CITCS" <?= (isset($college) && $college == 'CITCS') ? 'selected' : '' ?>>College of
						Information, Technology and Computer Studies (CITCS)</option>
					<option value="COM" <?= (isset($college) && $college == 'COM') ? 'selected' : '' ?>>College of Medicine
						(COM)</option>
					<option value="CTE" <?= (isset($college) && $college == 'CTE') ? 'selected' : '' ?>>College of Teacher
						Education</option>
					<option value="IPPG" <?= (isset($college) && $college == 'IPPG') ? 'selected' : '' ?>>Institute of
						Public Policy and Governance</option>
					<option value="ISW" <?= (isset($college) && $college == 'ISW') ? 'selected' : '' ?>>Institute of Social
						Work</option>
				</select>
			</div>

			<div class="col-md-4">
				<label class="control-label">Course</label>
				<select name="course" class="form-control" id="courseSelect" required>
					<option value="">Select Course</option>

					<!-- CAS -->
					<option data-college="CAS" value="BA Communication" <?= (isset($course) && $course == 'BA Communication') ? 'selected' : '' ?>>Bachelor of Arts in Communication</option>
					<option data-college="CAS" value="BS Psychology" <?= (isset($course) && $course == 'BS Psychology') ? 'selected' : '' ?>>Bachelor of Science in Psychology</option>

					<!-- CBA -->
					<option data-college="CBA" value="BSBA - HRDM" <?= (isset($course) && $course == 'BSBA - HRDM') ? 'selected' : '' ?>>BSBA - Human Resource Development Management</option>
					<option data-college="CBA" value="BSBA - Marketing" <?= (isset($course) && $course == 'BSBA - Marketing') ? 'selected' : '' ?>>BSBA - Marketing Management</option>
					<option data-college="CBA" value="BSBA - Operations" <?= (isset($course) && $course == 'BSBA - Operations') ? 'selected' : '' ?>>BSBA - Operations Management</option>

					<!-- COA -->
					<option data-college="COA" value="BS Accountancy" <?= (isset($course) && $course == 'BS Accountancy') ? 'selected' : '' ?>>Bachelor of Science in Accountancy</option>

					<!-- CCJ -->
					<option data-college="CCJ" value="BS Criminology" <?= (isset($course) && $course == 'BS Criminology') ? 'selected' : '' ?>>Bachelor of Science in Criminology</option>

					<!-- CITCS -->
					<option data-college="CITCS" value="BS Computer Science" <?= (isset($course) && $course == 'BS Computer Science') ? 'selected' : '' ?>>Bachelor of Science in Computer Science</option>
					<option data-college="CITCS" value="BS Information Technology" <?= (isset($course) && $course == 'BS Information Technology') ? 'selected' : '' ?>>Bachelor of Science in Information Technology
					</option>
					<option data-college="CITCS" value="ACT" <?= (isset($course) && $course == 'ACT') ? 'selected' : '' ?>>
						Associate in Computer Technology</option>

					<!-- COM -->
					<option data-college="COM" value="Doctor of Medicine" <?= (isset($course) && $course == 'Doctor of Medicine') ? 'selected' : '' ?>>Doctor of Medicine</option>

					<!-- CTE -->
					<option data-college="CTE" value="BEEd" <?= (isset($course) && $course == 'BEEd') ? 'selected' : '' ?>>
						Bachelor of Elementary Education (BEEd)</option>
					<option data-college="CTE" value="BSEd - Science" <?= (isset($course) && $course == 'BSEd - Science') ? 'selected' : '' ?>>BSEd - Science</option>
					<option data-college="CTE" value="BSEd - English" <?= (isset($course) && $course == 'BSEd - English') ? 'selected' : '' ?>>BSEd - English</option>
					<option data-college="CTE" value="BSEd - Social Science" <?= (isset($course) && $course == 'BSEd - Social Science') ? 'selected' : '' ?>>BSEd - Social Science</option>

					<!-- IPPG -->
					<option data-college="IPPG" value="BA Political Science" <?= (isset($course) && $course == 'BA Political Science') ? 'selected' : '' ?>>BA Political Science</option>
					<option data-college="IPPG" value="BS Public Administration" <?= (isset($course) && $course == 'BS Public Administration') ? 'selected' : '' ?>>BS Public Administration</option>

					<!-- ISW -->
					<option data-college="ISW" value="BS Social Work" <?= (isset($course) && $course == 'BS Social Work') ? 'selected' : '' ?>>Bachelor of Science in Social Work</option>
				</select>
			</div>
		</div>
		<hr>
	</form>
</div>

<script>
	const collegeSelect = document.getElementById('collegeSelect');
	const courseSelect = document.getElementById('courseSelect');

	function filterCourses() {
		const selectedCollege = collegeSelect.value;
		const courseOptions = courseSelect.querySelectorAll('option');

		courseOptions.forEach(option => {
			const college = option.getAttribute('data-college');
			if (!college) return; // Skip default "Select Course"
			option.style.display = (college === selectedCollege) ? 'block' : 'none';
		});

		// Reset selected course if not part of selected college
		const selectedCourse = courseSelect.options[courseSelect.selectedIndex];
		if (selectedCourse && selectedCourse.getAttribute('data-college') !== selectedCollege) {
			courseSelect.value = '';
		}
	}

	collegeSelect.addEventListener('change', filterCourses);
	window.addEventListener('DOMContentLoaded', filterCourses);
</script>

<script>
	$('#manage-person').submit(function (e) {
		e.preventDefault();
		start_load();
		$('#msg').html('');

		// Create FormData object to handle the form submission
		var formData = new FormData($(this)[0]);
		$.ajax({
			url: 'ajax.php?action=save_person',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			success: function (resp) {
				console.log("Response: ", resp);
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success');
					setTimeout(function () {
						location.reload();
					}, 1500);
				} else if (resp == 2) {
					$('#msg').html("<div class='alert alert-danger'>Name already exists.</div>");
					end_load();
				} else {
					$('#msg').html("<div class='alert alert-danger'>Error saving data.</div>");
					end_load();
				}
			}
		});
	});

</script>
