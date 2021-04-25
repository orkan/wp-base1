/*
 * ---------------------------------------------------------------------------------------------------------
 * Ork | Toggler
 *
 * <label for="abc" data-options='{...}'/><div id="abc"/>
 * Toogle targeted <div> by clicking on <label>
 * Options:
 * [once] => Disappear after click?
 */
ork.Toggler = ork.Toggler || {};

ork.Toggler = class {

	constructor( el ) {
		this.$el = $( el );
		this.options = $.extend({
			target: this.$el.attr( 'for' ) ?? '',
			once:   false,
		}, this.$el.data( 'options' ) );

		this.$el
			.on( 'click.ork', this.onClick )
			.on( 'done.ork', this.onDone )
			.show();

		this.$target = $( '#' + this.options.target )
			.on( 'toggle.ork', this.onToggle )
			.hide();
	}

	onClick = e => {
		e.preventDefault();
		this.$target.trigger( 'toggle.ork' );
	}

	onToggle = e => {
		this.$target.toggle();
		this.$el.trigger( 'done.ork' );
	}

	onDone = e => {
		if( this.options.once ) {
			this.$target.off( '.ork' ); // keep but unbind all .ork namespace events
			this.$el.remove(); // remove with events
		}
	}
}
