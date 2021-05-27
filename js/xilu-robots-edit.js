(function($) {
	wp.codeEditor.initialize( $('#robots-data'), cm_settings );

    $(".editor-notices").on("click", ".notice-dismiss", function(){
        $(this).parent(".notice").slideUp(function(){
			$(this).removeClass("notice-success notice-error");
		});
    });

	$("#submit").on("click", function(){
		$(this).next(".spinner").addClass('is-active');
		$(".notice").slideUp(function(){
			$(this).removeClass("notice-success notice-error");
		});
	});

	$( 'form[action="?page=robots-edit"]' ).on( "submit", function( e ) {
		
		var form = $(this);
    	var ajaxurl = form.data('ajaxurl');
		var btn = form.find('[type="submit"]');

		var data = {
				'action': 'save_robots_file',
                'robots': $('[name="robots-data"]').val()
            };

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data
		}).done(function( data ) {
			val = JSON.parse( data );
			$(".submit .spinner").removeClass('is-active');
			setTimeout( function(){
				$('.notice:hidden').addClass(val.css).find("p").text(val.mesg);
				$('.notice:hidden').slideDown();
			}, 300 )
			
		});
		e.preventDefault();

	});

})( jQuery );