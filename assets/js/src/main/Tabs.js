/**
 * ---------------------------------------------------------------------------------------------------------
 * Ork | Tabs
 * @link https://www.w3schools.com/w3css/w3css_tabulators.asp
 */
ork.Tabs = ork.Tabs || {};

ork.Tabs = class {

	constructor( el ) {
		var $el = $( el ), self = this;

		this.options = $.extend({
			id:    el.id ?? 'ork-tabs',
			tab:   0,
			class: 'ork-tabs-active',
		}, $el.data( 'options' ) );

		this.$tabs = [];
		this.$body = [];

		$el.children().each( function( i ) {
			self.$tabs[i] = $( this ).on( 'click', { tabId: i }, self.onClick ).children( ':first' );
			self.$body[i] = $( `#${self.options.id}-body${i}` );
		});

		this.$tabs.length && this.$tabs[this.options.tab].trigger( 'click' );
	}

	onClick = e => {
		e.preventDefault();
		this.$tabs.forEach( ( $tab, i ) => {
			var show = i == e.data.tabId;
			this.$tabs[i].toggleClass( this.options.class, show );
			this.$body[i].toggle( show );
		});
	}
}
