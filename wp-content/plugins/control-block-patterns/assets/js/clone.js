( function ( $, ctrlbp ) {
	'use strict';

	// Object holds all methods related to fields' index when clone
	var cloneIndex = {
		/**
		 * Set index for fields in a .ctrlbp-clone
		 * @param $inputs .ctrlbp-clone element
		 * @param index Index value
		 */
		set: function ( $inputs, index ) {
			$inputs.each( function () {
				var $field = $( this );

				// Name attribute
				var name = this.name;
				if ( name && ! $field.closest( '.ctrlbp-group-clone' ).length ) {
					$field.attr( 'name', cloneIndex.replace( index, name, '[', ']', false ) );
				}

				// ID attribute
				var id = this.id;
				if ( id ) {
					$field.attr( 'id', cloneIndex.replace( index, id, '_', '', true, true ) );
				}

				$field.trigger( 'update_index', index );
			} );
		},

		/**
		 * Replace an attribute of a field with updated index
		 * @param index New index value
		 * @param value Attribute value
		 * @param before String before returned value
		 * @param after String after returned value
		 * @param alternative Check if attribute does not contain any integer, will reset the attribute?
		 * @param isEnd Check if we find string at the end?
		 * @return string
		 */
		replace: function ( index, value, before, after, alternative, isEnd ) {
			before = before || '';
			after = after || '';

			if ( typeof alternative === 'undefined' ) {
				alternative = true;
			}

			var end = isEnd ? '$' : '';

			var regex = new RegExp( cloneIndex.escapeRegex( before ) + '(\\d+)' + cloneIndex.escapeRegex( after ) + end ),
				newValue = before + index + after;

			return regex.test( value ) ? value.replace( regex, newValue ) : (alternative ? value + newValue : value );
		},

		/**
		 * Helper function to escape string in regular expression
		 * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
		 * @param string
		 * @return string
		 */
		escapeRegex: function ( string ) {
			return string.replace( /[.*+?^${}()|[\]\\]/g, "\\$&" );
		},

		/**
		 * Helper function to create next index for clones
		 * @param $container .ctrlbp-input container
		 * @return integer
		 */
		nextIndex: function ( $container ) {
			var nextIndex = $container.data( 'next-index' );
			$container.data( 'next-index', nextIndex + 1 );
			return nextIndex;
		}
	};

	// Object holds all method related to fields' value when clone.
	var cloneValue = {
		setDefault: function() {
			var $field = $( this );

			if ( true !== $field.data( 'clone-default' ) ) {
				return;
			}

			var type = $field.attr( 'type' ),
				defaultValue = $field.data( 'default' );

			if ( 'radio' === type ) {
				$field.prop( 'checked', $field.val() === defaultValue );
			} else if ( $field.hasClass( 'ctrlbp-checkbox' ) || $field.hasClass( 'ctrlbp-switch' ) ) {
				$field.prop( 'checked', !! defaultValue );
			} else if ( $field.hasClass( 'ctrlbp-checkbox_list' ) ) {
				var value = $field.val();
				$field.prop( 'checked', Array.isArray( defaultValue ) ? -1 !== defaultValue.indexOf( value ) : value == defaultValue );
			} else if ( 'select' === type ) {
				$field.find( 'option[value="' + defaultValue + '"]' ).prop( 'selected', true );
			} else if ( ! $field.hasClass( 'ctrlbp-hidden' ) ) {
				$field.val( defaultValue );
			}
		},
		clear: function() {
			var $field = $( this ),
				type = $field.attr( 'type' );

			if ( 'radio' === type || 'checkbox' === type ) {
				$field.prop( 'checked', false );
			} else if ( 'select' === type ) {
				$field.prop( 'selectedIndex', - 1 );
			} else if ( ! $field.hasClass( 'ctrlbp-hidden' ) ) {
				$field.val( '' );
			}
		}
	};

	/**
	 * Clone fields
	 * @param $container A div container which has all fields
	 */
	function clone( $container ) {
		var $last = $container.children( '.ctrlbp-clone' ).last(),
			$clone = $last.clone(),
			nextIndex = cloneIndex.nextIndex( $container );

		// Clear fields' values.
		var $inputs = $clone.find( ctrlbp.inputSelectors );
		$inputs.each( cloneValue.clear );

		// Insert clone.
		$clone.insertAfter( $last );

		// Trigger custom event for the clone instance. Required for Group extension to update sub fields.
		$clone.trigger( 'clone_instance', nextIndex );

		// Set fields index. Must run before trigger clone event.
		cloneIndex.set( $inputs, nextIndex );

		// Set fields' default values: do after index is set to prevent previous radio fields from unchecking.
		$inputs.each( cloneValue.setDefault );

		// Trigger custom clone event.
		$inputs.trigger( 'clone', nextIndex );

		// After cloning fields.
		$inputs.trigger( 'after_clone', nextIndex );

		// Trigger custom change event for MB Blocks to update block attributes.
		$inputs.first().trigger( 'ctrlbp_change' );
	}

	/**
	 * Hide remove buttons when there's only 1 of them
	 *
	 * @param $container .ctrlbp-input container
	 */
	function toggleRemoveButtons( $container ) {
		var $clones = $container.children( '.ctrlbp-clone' );
		$clones.children( '.remove-clone' ).toggle( $clones.length > 1 );

		// Recursive for nested groups.
		$container.find( '.ctrlbp-input' ).each( function () {
			toggleRemoveButtons( $( this ) );
		} );
	}

	/**
	 * Toggle add button
	 * Used with [data-max-clone] attribute. When max clone is reached, the add button is hid and vice versa
	 *
	 * @param $container .ctrlbp-input container
	 */
	function toggleAddButton( $container ) {
		var $button = $container.children( '.add-clone' ),
			maxClone = parseInt( $container.data( 'max-clone' ) ),
			numClone = $container.children( '.ctrlbp-clone' ).length;

		$button.toggle( isNaN( maxClone ) || ( maxClone && numClone < maxClone ) );
	}

	function addClone( e ) {
		e.preventDefault();

		var $container = $( this ).closest( '.ctrlbp-input' );
		clone( $container );

		toggleRemoveButtons( $container );
		toggleAddButton( $container );
		sortClones.apply( $container[0] );
	}

	function removeClone( e ) {
		e.preventDefault();

		var $this = $( this ),
			$container = $this.closest( '.ctrlbp-input' );

		// Remove clone only if there are 2 or more of them
		if ( $container.children( '.ctrlbp-clone' ).length < 2 ) {
			return;
		}

		$this.parent().trigger( 'remove' ).remove();
		toggleRemoveButtons( $container );
		toggleAddButton( $container );

		// Trigger custom change event for MB Blocks to update block attributes.
		$container.find( ctrlbp.inputSelectors ).first().trigger( 'ctrlbp_change' );
	}

	/**
	 * Sort clones.
	 * Expect this = .ctrlbp-input element.
	 */
	function sortClones() {
		var $container = $( this );

		if ( undefined !== $container.sortable( 'instance' ) ) {
			return;
		}
		if ( 0 === $container.children( '.ctrlbp-clone' ).length ) {
			return;
		}

		$container.sortable( {
			handle: '.ctrlbp-clone-icon',
			placeholder: ' ctrlbp-clone ctrlbp-sortable-placeholder',
			items: '> .ctrlbp-clone',
			start: function ( event, ui ) {
				// Make the placeholder has the same height as dragged item
				ui.placeholder.height( ui.item.outerHeight() );
			},
			stop: function( event, ui ) {
				ui.item.trigger( 'ctrlbp_init_editors' );
				ui.item.find( ctrlbp.inputSelectors ).first().trigger( 'ctrlbp_change' );
			}
		} );
	}

	function start() {
		var $container = $( this );
		toggleRemoveButtons( $container );
		toggleAddButton( $container );

		$container.data( 'next-index', $container.children( '.ctrlbp-clone' ).length );
		sortClones.apply( this );
	}

	function init( e ) {
		$( e.target ).find( '.ctrlbp-input' ).each( start );
	}

	ctrlbp.$document
		.on( 'ctrlbp_ready', init )
		.on( 'click', '.add-clone', addClone )
		.on( 'click', '.remove-clone', removeClone );

	// Export for use outside.
	ctrlbp.cloneIndex = cloneIndex;
	ctrlbp.cloneValue = cloneValue;
	ctrlbp.sortClones = sortClones;
	ctrlbp.toggleRemoveButtons = toggleRemoveButtons;
	ctrlbp.toggleAddButton = toggleAddButton;
} )( jQuery, ctrlbp );