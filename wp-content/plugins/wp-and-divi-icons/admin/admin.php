<?php
	echo('<div id="agsdi-admin-page" class="wrap"><h1>'.esc_html__(self::PLUGIN_NAME, 'ds-icon-epxansion').'</h1>
			<div id="agsdi-instructions">
				<h2>'.esc_html__('Instructions', 'ds-icon-expansion').'</h2>'
				.'<p>'.sprintf(
							esc_html__('Easily insert one of the 300+ icons provided by %s when using the WordPress visual editor to create and edit posts, pages, and other content! Simply click on the %s icon in the editor\'s toolbar to open the icon insertion window.', 'ds-icon-expansion'),
							self::PLUGIN_NAME,
							'<span data-icon="agsdix-sao-design" class="agsdi-icon"></span>'
						).'</p>'
				.'<p>'.sprintf(
							esc_html__('If you use the %sDivi or Extra theme%s or the %sDivi Builder%s, you can also use the 300+ icons provided by %s anywhere that the Divi Builder allows you to specify an icon, such as in Buttons, Blurbs, and much more! Works in both the Divi Builder and the Visual Builder.', 'ds-icon-expansion'),
							'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=32248_0_1_10" target="_blank">',
							'</a>',
							'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=32248_0_1_10" target="_blank">',
							'</a>',
							self::PLUGIN_NAME
						).'</p>
			</div>');
?>
<?php
	echo('<h2>'.esc_html__('Check out these products too!', 'ds-icon-expansion').'</h2>
		<ul>
	');
	foreach (self::getCreditPromos('admin-page', true) as $promo) {
		echo('<li>'.$promo.'</li>');
	}
	echo('</ul>');

	echo('<p><em>Divi is a registered trademark of Elegant Themes, Inc. This product is not affiliated with nor endorsed by Elegant Themes. Links to the Elegant Themes website on this page are affiliate links.</em></p>');

	echo('</div>');