$( function() {
	$('.btn-clear-form').on('click', function() {
		this.form.reset();
	});
	$('.btn-conf').on('click', function(e) {
		return confirm("Deseja prosseguir?");
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
	$('.open-content').on('click', function() {
		var target = $(this).data('target');
		$(target).toggle();
	});
});