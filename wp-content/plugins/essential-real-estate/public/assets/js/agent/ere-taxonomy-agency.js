(function ($) {
	'use strict';
	$(document).ready(function () {
		function ere_init_google_map_in_tab() {
			$('[data-toggle="tab"]', '.nav-tabs').on('shown.bs.tab', function () {
				var $this = $(this),
					tab_id = $this.attr('href').substring(1, $this.attr('href').length),
					current_tab = $this.closest('.nav-tabs').next('.tab-content').find('[id="'+tab_id+'"]');
				if(current_tab.find('#map-property-single').length > 0 && typeof google != 'undefined') {
					google.maps.event.trigger(window, 'resize', {});
				}
			});
		}
		ere_init_google_map_in_tab();
		ERE.contact_agent_by_email();
	});
})(jQuery);