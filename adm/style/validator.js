(function($){
	'use strict';

	var $validationResult = $('#validation-result');
	$('#validate-css').on('click', function(e) {
		e.preventDefault();

		var data = {
			text: $('#customcss').val(),
			output: 'html',
			lang: $('html').attr('lang')
		};
		$.get('https://jigsaw.w3.org/css-validator/validator', data, function(html) {
			var $html = $(html);

			if ($html.find('#congrats').length) {
				// no errors - display success message
				$validationResult.html($validationResult.attr('data-good'))
			} else {
				// extract errors
				var $errors = $html.find('#errors');

				// remove unnecessary parts
				$errors.find('h4').remove();

				// print errors
				$validationResult.html($errors);
			}
		});
	});
})(jQuery);
