( function ( $, ctrlbp ) {
	'use strict';

	function toggleAll( e ) {
		e.preventDefault();

		var $this = $( this ),
			$select = $this.parent().siblings( 'select' );

		if ( 'none' === $this.data( 'type' ) ) {
			$select.val( [] ).trigger( 'change' );
			return;
		}
		var selected = [];
		$select.find( 'option' ).each( function ( index, option ) {
			selected.push( option.value );
		} );
		$select.val( selected ).trigger( 'change' );
	};

	ctrlbp.$document.on( 'click', '.ctrlbp-select-all-none a', toggleAll );
} )( jQuery, ctrlbp );