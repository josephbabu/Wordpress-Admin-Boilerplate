(function ( $ ) {
	"use strict";

	$(function () {

		/**
		 * This will be needed for metaboxes in admin page – to drag them and re-order.
		 */

		jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');

		postboxes.add_postbox_toggles('appearance_page_simplefavicon');

	});

}(jQuery));