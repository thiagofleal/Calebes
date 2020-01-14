$(function() {
	$('.btn-clear-form').on('click', function() {
		this.form.reset();
	});
	$('.enable-on-mark').on('change', function() {
		var enable = $(this).data('enable');

		for(var item in $('.enable-on-mark').toArray()) {
			var disable = $(item).data('enable');
			$(disable).attr("disabled", "disabled");
		}

		if ($(this).is(":checked")) {
			$(enable).removeAttr("disabled");
		}
	});
});