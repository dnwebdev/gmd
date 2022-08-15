jQuery(document).ready(function(){
	$('.carousel').carousel();

		if ($('.same-height').length > 0) {
			$('.same-height').matchHeight();
		}
		
	
	function openMenu(selector) {
		jQuery(selector).click(function(){
			jQuery(selector).next().toggleClass('is-active');
		});
	}

	openMenu('.account-badge');

	if ($( '.cd-select' ).length > 0) {
		$( '.cd-balance' ).dropdown( {
			gutter : 5,
			order: 10
		} );

		$( '.cd-order-status' ).dropdown( { 
			gutter : 5,
			order: 2
		} );
	}
	
	function equalHeight(referenceEl, targetEl) {
		var height = jQuery(referenceEl).height();

		jQuery(targetEl).height(height);
	}
	
	equalHeight('.container', '.left-menu');	

	// $(window).load(function() {
	// 	equalHeight('.container', '.left-menu');
	// });


	$(window).resize(function(){
		equalHeight('.container', '.left-menu');
	});
});