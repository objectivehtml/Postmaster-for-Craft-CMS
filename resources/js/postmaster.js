(function() {

	$(document).ready(function() {

		$('select.show-onchange, .show-onchange select').change(function() {
			var $select = $(this).find(':checked');

			$(this).find('option').each(function() {
				$($(this).data('target')).addClass('hidden');
			});

			$($select.data('target')).removeClass('hidden');
		})
		.change();

    	$('.delete-parcel').click(function() {
    		if (confirm('Are you sure you want to delete this thing parcel?')) {
			    window.location = 'postmaster/parcel/delete/' + $(this).data('id');
			}
    	});

	});

}());