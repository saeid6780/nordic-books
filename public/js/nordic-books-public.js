jQuery(function($){
	var modal = $('#nordic-book-modal');
	var addBtn = $('.nordic-add-book-btn');
	var closeBtn = $('.nordic-modal-close, .nordic-cancel-btn');
	var form = $('#nordic-add-book-form');
	var messageBox = $('.nordic-form-message');
	var tbody = $('#nordic-books-tbody');

	// open modal
	addBtn.on('click', function(e){
		e.preventDefault();
		messageBox.html('');
		form[0].reset();
		modal.attr('aria-hidden','false').fadeIn(150);
	});

	// close modal
	closeBtn.on('click', function(e){
		e.preventDefault();
		modal.attr('aria-hidden','true').fadeOut(150);
	});

	// click outside to close
	modal.on('click', function(e){
		if ( e.target === this ) {
			modal.attr('aria-hidden','true').fadeOut(150);
		}
	});

	// simple client-side validation
	function validateForm() {
		var title = $('#nordic-title').val().trim();
		var author = $('#nordic-author').val().trim();
		var year = $('#nordic-year').val().trim();
		if ( title === '' || author === '' || year === '' ) {
			return false;
		}
		if ( isNaN(year) || parseInt(year,10) <= 0 ) {
			return false;
		}
		return true;
	}

	form.on('submit', function(e){
		e.preventDefault();
		messageBox.removeClass('error success').text('');

		if ( ! validateForm() ) {
			messageBox.addClass('error').text( NordicBooks.strings.validation );
			return;
		}

		var data = {
			action: 'nordic_add_book',
			nonce: NordicBooks.nonce,
			title: $('#nordic-title').val().trim(),
			author: $('#nordic-author').val().trim(),
			published_year: $('#nordic-year').val().trim()
		};

		$('.nordic-submit-btn').prop('disabled', true).text( NordicBooks.strings.saving );

		$.post( NordicBooks.ajax_url, data )
			.done(function( response ){
				if ( response.success ) {
					messageBox.addClass('success').text( NordicBooks.strings.saved );
					// insert row at top (or remove 'no books' row)
					var $newRow = $( response.data.row );
					// remove empty-row if exists
					tbody.find('.nordic-empty-row').remove();
					tbody.prepend( $newRow );
					// close modal after short delay
					setTimeout(function(){
						modal.attr('aria-hidden','true').fadeOut(150);
					}, 600);
				} else {
					var msg = response.data && response.data.message ? response.data.message : NordicBooks.strings.error;
					messageBox.addClass('error').text( msg );
				}
			})
			.fail(function(){
				messageBox.addClass('error').text( NordicBooks.strings.error );
			})
			.always(function(){
				$('.nordic-submit-btn').prop('disabled', false).text( 'Save' );
			});
	});

	// apply inline color styles from localized data
	(function applyColors(){
		var primary = NordicBooks.primary_color || '#0d6efd';
		var secondary = NordicBooks.secondary_color || '#f8f9fa';

		// simple approach: set CSS variables on wrapper
		$('.nordic-book-list-wrapper').css({
			'--nordic-primary': primary,
			'--nordic-secondary': secondary
		});
	})();

});
