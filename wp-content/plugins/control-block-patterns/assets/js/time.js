( function ( $, ctrlbp, i18n ) {
	'use strict';

	/**
	 * Transform an input into a time picker.
	 */
	function transform() {
		var $this = $( this ),
			options = $this.data( 'options' ),
			$inline = $this.siblings( '.ctrlbp-datetime-inline' ),
			current = $this.val();

		$this.siblings( '.ui-datepicker-append' ).remove();  // Remove appended text

		options.onSelect = function() {
			$this.trigger( 'change' );
		}
		options.beforeShow = function( i ) {
			if ( $( i ).prop( 'readonly' ) ) {
				return false;
			}
		}

		if ( ! $inline.length ) {
			$this.removeClass( 'hasDatepicker' ).timepicker( options );
			return;
		}

		options.altField = '#' + $this.attr( 'id' );
		$inline
			.removeClass( 'hasDatepicker' )
			.empty()
			.prop( 'id', '' )
			.timepicker( options )
			.timepicker( 'setTime', current );
	}

	// Set language if available
	function setTimeI18n() {
		if ( $.timepicker.regional.hasOwnProperty( i18n.locale ) ) {
			$.timepicker.setDefaults( $.timepicker.regional[i18n.locale] );
		} else if ( $.timepicker.regional.hasOwnProperty( i18n.localeShort ) ) {
			$.timepicker.setDefaults( $.timepicker.regional[i18n.localeShort] );
		}
	}

	function init( e ) {
		$( e.target ).find( '.ctrlbp-time' ).each( transform );
	}

	setTimeI18n();
	ctrlbp.$document
		.on( 'ctrlbp_ready', init )
		.on( 'clone', '.ctrlbp-time', transform );
} )( jQuery, ctrlbp, CTRLBP_Time );
