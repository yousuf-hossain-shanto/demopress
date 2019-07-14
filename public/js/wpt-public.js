(function( $ ) {
	'use strict';

	$(document).ready(function () {

	})

	$(document).on('submit', '#temp-form', function (e) {
		e.preventDefault();
		var data = $(this).serialize() + '&action=temp-signup';

		$.ajax({
			method: 'POST',
			url: $(this).attr('action'),
			data: data
		}).done((res) => {
			if (res.length) {
                var mes = res[0];
                $('.temp .err').hide();
                $('.temp .success').text(mes).show();
            }
		}).fail(err => {
		    if (err.responseJSON.length) {
                var mes = err.responseJSON[0];
                $('.temp .success').hide();
                $('.temp .err').text(mes).show();
            }
        })

	})

})( jQuery );
