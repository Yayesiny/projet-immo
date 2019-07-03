(function($) {
	"use strict";

	//clear form after submit
	$( document ).ajaxComplete(function( event, xhr, settings ) {
		try{
			var $respo = $.parseXML(xhr.responseText);
			//exit on error
			if ($($respo).find('wp_error').length) return;
			if ($($respo).find('.gsf-term-meta-item-wrapper').length) {
				return;
			}

			var $taxWrappe = $('.gsf-term-meta-wrapper'),
				taxonomy = $taxWrappe.data('taxonomy');
			$.ajax({
				type: "GET",
				url: gsfMetaData.ajax_url,
				data: {
					action: 'gsf_tax_meta_form',
					taxonomy: taxonomy
				},
				success : function(res) {
					$taxWrappe.html(res);
					for (var i = 0; i < GSFFieldsConfig.fieldInstance.length; i++) {
						GSFFieldsConfig.fieldInstance[i].init();
					}
					GSFFieldsConfig.onReady.init();
				}
			});

		}catch(err) {}
	});
})(jQuery);