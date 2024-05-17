/*
 * ---------------------------------------------------------------------------------------------------------
 * Ork | Base
 */
ork.Base = {
	/**
	 * Get element ID from name.
	 */
	getId( name ) {
		return '#' + ork.name2id[name];
	},

	/**
	 * Get element from name.
	 */
	getElement( name ) {
		return document.getElementById( ork.name2id[name] );
	},

	/**
	 * Logger.
	 */
	log( msg, type = 'log' ) {
		if( 'debug' === type && !ork.debug ) {
			return;
		}
		else {
			type = 'log';
		}

		console[type]( msg );
	},

	debug( msg ) {
		this.log( msg, 'debug' );
	},

	/**
	 * Clear FORM inputs.
	 */
	clearForm( form ) {
		form.reset();
		Array.from( form.elements ).forEach( input => {
			if( input.disabled ) {
				return;
			}
			switch( input.type.toLowerCase() ) {
				case 'text':
				case 'password':
				case 'textarea':
				case 'hidden':
					input.value = '';
					break;

				case 'radio':
				case 'checkbox':
					input.checked = false;
					break;

				case 'select-one':
				case 'select-multi':
					input.selectedIndex = -1;
					break;
			}
		});
	},

	/**
	 * [Input] Show/hide spinner
	 */
	showSpinner( id, show = true ) {
		$( id + '_spin' ).toggleClass( 'is-active', show );
	},

	/**
	 * Must be called as: await usleep(1); from within another async function! :(
	 */
	async usleep( usec ) {
		return await new Promise( (resolve, reject) => setTimeout( () => resolve(1), usec ) );
	},
};
