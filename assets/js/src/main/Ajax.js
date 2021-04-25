/*
 * ---------------------------------------------------------------------------------------------------------
 * Ork | Ajax
 */
ork.Ajax = {
	/**
	 * Ajax request
	 */
	async fetch( action, data = {}, opts = {} ) {
		data.action = action;
		data[ork.nonce.name] = ork.nonce.action;

		// Deaults...
		opts = { ...{
			method: 'POST',
			signal:  null,
			spinner: '',
			body: this.getFormData( data ),
		}, ...opts };

		let json, status;
		this.spinner( opts.spinner, true );

		const Response = await fetch( ork.url, opts )
			.then( Response => {
				status = `[${Response.status}] ${Response.statusText}`;
				ork.Base.debug( Response );
				return Response.text();
			})
			.catch( E => {
				throw new Error( E ); // Connection errors
			});

		this.spinner( opts.spinner, false );

		try {
			json = JSON.parse( Response );
		} catch( E ) {
			throw new Error( E ); // Data errors
		}

		if( !json || !json.success ) {
			throw new Error( json.data || status ); // WP errors
		}

		return json.data;
	},

	/**
	 * Convert Object to FormData instance.
	 */
	getFormData( obj ) {
		const Data = new FormData();
		for( const [name, value] of Object.entries( obj ) ) {
			Data.append( name, value );
		}
		return Data;
	},

	/**
	 * [Input] Show/hide spinner
	 */
	spinner( id, show = true, className = 'is-active' ) {
		if( id ) {
			const ClassList = document.querySelector( id ).classList;
			show ? ClassList.add( className ) : ClassList.remove( className );
		}
	},
};