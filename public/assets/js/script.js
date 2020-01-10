$(function() {
	$('.btn-clear-form').on('click', function() {
		this.form.reset();
	});
	$('.enable-on-mark').on('change', function() {
		var enable = $(this).data('enable');
		$(enable).attr("disabled", true);

		if ($(this).is(":checked")) {
			$(enable).attr("disabled", false);
		}
	});
});