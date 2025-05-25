
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
