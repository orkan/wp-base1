window.ork = window.ork || {};

(function ($) {

	/* {FILE: main/Base.js} */
	/* {FILE: main/Ajax.js} */

	/*
	 * ---------------------------------------------------------------------------------------------------------
	 * onLoad
	 */
	$(() => {
		let Controller;
		// [selector] Handle onChange event on <select>
		$( ork.Base.getId( 'selector_select' ) )
			.on( 'change', async (Event) => {
				const name = Event.currentTarget.value;
				const Result = ork.Base.getElement( 'selector_result' );
				Result.value = '---';

				if( name ) {
					try {
						// Abort previous Request if in progress (race condition)
						Controller && Controller.abort();
						Controller = new AbortController();

						// Ajax request
						Result.value = ork.l10n.wait;
						Result.value = await ork.Ajax.fetch( ork.ajaxer.action.selector, { name }, {
							signal: Controller.signal,
							spinner: '#selector_spin',
						});

						Controller = null;
					} catch( E ) {
						Result.value = 'Error: ' + E.message;
					}
				}
			})
			.change();
	});

	$(() => {
		let Controller;
		// [texter] Handle onInput event on <text>
		$( ork.Base.getId( 'texter_text' ) )
			.on( 'input', async (Event) => {
				const text = Event.currentTarget.value;
				const error = ork.Base.getElement( 'texter_error' ).checked;
				const Result = ork.Base.getElement( 'texter_result' );
				try {
					// Abort previous Request if in progress (race condition)
					Controller && Controller.abort();
					Controller = new AbortController();

					// Ajax request
					Result.value = await ork.Ajax.fetch( ork.ajaxer.action.echotext, { text, error }, {
						signal: Controller.signal,
						spinner: '#texter_spin',
					});

					Controller = null;
				} catch( E ) {
					Result.value = 'Error: ' + E.message;
				}
			});
	});

	$(() => {
		// [cache] Clear input fields cache
		$( ork.Base.getId( 'cache_btn' ) )
			.on( 'click', async (Event) => {
				const Div = document.getElementById( 'cache_result' );
				try {
					Event.currentTarget.disabled = true;
					Div.innerHTML = '';
					Div.innerHTML = await ork.Ajax.fetch( ork.ajaxer.action.flushcache, {}, {
						method: 'GET',
						spinner: '#cache_spin',
					});
					Event.currentTarget.disabled = false;
				} catch( E ) {
					Div.innerHTML = 'Error: ' + E.message;
				} finally {
					Event.currentTarget.disabled = false;
				}
			});
	});
})( jQuery );
