/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder by Elegant Themes, licensed GPLv2, used under GPLv3 in this project by special permission (see ../license.txt).
*/

(function($) {
	console.log('Divi Icon Expansion Pack: Fallback method loaded');
	$('body').addClass('agsdi-no-css');
	var iconsCss = '';
	var $iconsStyle = $('<style>').appendTo('head:first');

	// Special pre-processing for the Shop module
	$('.et_pb_module.et_pb_shop[data-icon^=\'agsdi-\']').each(function() {
		var $this = $(this);
		var thisIcon = $this.data('icon');
		$this
			.addClass('agsdi-nocss-skip')
			.find('.et_overlay')
				.attr('data-icon', thisIcon);
	});

	$('*[data-icon^=\'agsdi-\']:not(.agsdi-nocss-skip),*[data-icon^=\'agsdix-s\']:not(.agsdi-nocss-skip)').each(function(iconNumber) {
		var $this = $(this);
		var iconName = $this.data('icon');
		
		var afterColor = getComputedStyle(this, 'after').getPropertyValue('color');
		var elementColor = getComputedStyle(this).getPropertyValue('color');
		
		/*
		Trying to detect whether icon is before or after seems to be problematic in IE
		var iconIsBefore = afterStyle.getPropertyValue('display') == 'none' || afterStyle.getPropertyValue('content') == 'none';
		var iconColor = (iconIsBefore ? getComputedStyle(this, 'before') : afterStyle).getPropertyValue('color');
		*/
		
		
		// Guess at which color is the right one based on which color is different
		// from the color of the element itself
		if (afterColor && afterColor != elementColor) {
			var iconColor = afterColor;
		} else {
			var beforeColor = getComputedStyle(this, 'before').getPropertyValue('color');
			if (beforeColor) {
				var iconColor = beforeColor;
			} else {
				var iconColor = elementColor;
			}
		}
		
		if (iconName[5] == 'x') {
			var secondDashPos = iconName.indexOf('-', 7);
			if (secondDashPos == -1) {
				return;
			}
			var iconSet = iconName.substr(0, secondDashPos);
		} else {
			var iconSet = 'agsdi';
		}
		
		switch (iconSet) {
			case 'agsdi':
				var iconUrl = ags_divi_icons_config.pluginDirUrl + '/svg/ags_icon_' + iconName.substr(6) + '.svg';
				break;
			case 'agsdix-smt':
				var iconUrl = ags_divi_icons_config.pluginDirUrl + '/icon-packs/material/fallback/' + iconName.substr(12) + '.svg';
				break;
			case 'agsdix-sao':
				var iconUrl = ags_divi_icons_config.pluginDirUrl + '/icon-packs/ags-angular/icons/o_' + iconName.substr(11) + '.svg';
				break;
			default:
				return;
		}
		
		$this.addClass('agsdi-nocss-icon agsdi-nocss-icon-' + iconNumber);
		
		$.get(iconUrl, null, function(svg) {
			
			var $svg = $(svg).find('svg:first');
			$svg.find('[stroke!=\'none\']').attr('stroke', iconColor);
			$svg.find('[fill!=\'none\']').attr('fill', iconColor);
			$svg.prepend('<style>*{stroke-width: 2px;}</style>');
			
			var $meta = $svg.children('metadata');
			if ($meta.length) {
				$iconsStyle.append('\n\n/*\nOriginal SVG metadata for the following line:\n' + $meta.text() + '\n*/\n');
				$meta.remove();
			}
			
			var $tempContainer = $('<div>').append($svg);
			
			// SVG Data URI based on https://css-tricks.com/using-svg/
			$iconsStyle.append('.agsdi-nocss-icon-' + iconNumber + ':before,.agsdi-nocss-icon-' + iconNumber + ':after{background-image:url(data:image/svg+xml;base64,' + $.agsdi_base64.encode($tempContainer.html()) + ');}\n');
		}, 'xml');
		
	});
})(jQuery);

/* End code by Aspen Grove Studios */





/*
 * Original code (c) 2010 Nick Galbreath
 * http://code.google.com/p/stringencoders/source/browse/#svn/trunk/javascript
 *
 * jQuery port (c) 2010 Carlo Zottmann
 * http://github.com/carlo/jquery-base64
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
*/

/* base64 encode/decode compatible with window.btoa/atob
 *
 * window.atob/btoa is a Firefox extension to convert binary data (the "b")
 * to base64 (ascii, the "a").
 *
 * It is also found in Safari and Chrome.  It is not available in IE.
 *
 * if (!window.btoa) window.btoa = $.base64.encode
 * if (!window.atob) window.atob = $.base64.decode
 *
 * The original spec's for atob/btoa are a bit lacking
 * https://developer.mozilla.org/en/DOM/window.atob
 * https://developer.mozilla.org/en/DOM/window.btoa
 *
 * window.btoa and $.base64.encode takes a string where charCodeAt is [0,255]
 * If any character is not [0,255], then an exception is thrown.
 *
 * window.atob and $.base64.decode take a base64-encoded string
 * If the input length is not a multiple of 4, or contains invalid characters
 *   then an exception is thrown.
 */
 
jQuery.agsdi_base64 = ( function( $ ) {
  
  var _PADCHAR = "=",
    _ALPHA = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    _VERSION = "1.0";


  function _getbyte64( s, i ) {
    // This is oddly fast, except on Chrome/V8.
    // Minimal or no improvement in performance by using a
    // object with properties mapping chars to value (eg. 'A': 0)

    var idx = _ALPHA.indexOf( s.charAt( i ) );

    if ( idx === -1 ) {
      throw "Cannot decode base64";
    }

    return idx;
  }
  
  
  function _decode( s ) {
    var pads = 0,
      i,
      b10,
      imax = s.length,
      x = [];

    s = String( s );
    
    if ( imax === 0 ) {
      return s;
    }

    if ( imax % 4 !== 0 ) {
      throw "Cannot decode base64";
    }

    if ( s.charAt( imax - 1 ) === _PADCHAR ) {
      pads = 1;

      if ( s.charAt( imax - 2 ) === _PADCHAR ) {
        pads = 2;
      }

      // either way, we want to ignore this last block
      imax -= 4;
    }

    for ( i = 0; i < imax; i += 4 ) {
      b10 = ( _getbyte64( s, i ) << 18 ) | ( _getbyte64( s, i + 1 ) << 12 ) | ( _getbyte64( s, i + 2 ) << 6 ) | _getbyte64( s, i + 3 );
      x.push( String.fromCharCode( b10 >> 16, ( b10 >> 8 ) & 0xff, b10 & 0xff ) );
    }

    switch ( pads ) {
      case 1:
        b10 = ( _getbyte64( s, i ) << 18 ) | ( _getbyte64( s, i + 1 ) << 12 ) | ( _getbyte64( s, i + 2 ) << 6 );
        x.push( String.fromCharCode( b10 >> 16, ( b10 >> 8 ) & 0xff ) );
        break;

      case 2:
        b10 = ( _getbyte64( s, i ) << 18) | ( _getbyte64( s, i + 1 ) << 12 );
        x.push( String.fromCharCode( b10 >> 16 ) );
        break;
    }

    return x.join( "" );
  }
  
  
  function _getbyte( s, i ) {
    var x = s.charCodeAt( i );

    if ( x > 255 ) {
      throw "INVALID_CHARACTER_ERR: DOM Exception 5";
    }
    
    return x;
  }


  function _encode( s ) {
    if ( arguments.length !== 1 ) {
      throw "SyntaxError: exactly one argument required";
    }

    s = String( s );

    var i,
      b10,
      x = [],
      imax = s.length - s.length % 3;

    if ( s.length === 0 ) {
      return s;
    }

    for ( i = 0; i < imax; i += 3 ) {
      b10 = ( _getbyte( s, i ) << 16 ) | ( _getbyte( s, i + 1 ) << 8 ) | _getbyte( s, i + 2 );
      x.push( _ALPHA.charAt( b10 >> 18 ) );
      x.push( _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) );
      x.push( _ALPHA.charAt( ( b10 >> 6 ) & 0x3f ) );
      x.push( _ALPHA.charAt( b10 & 0x3f ) );
    }

    switch ( s.length - imax ) {
      case 1:
        b10 = _getbyte( s, i ) << 16;
        x.push( _ALPHA.charAt( b10 >> 18 ) + _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) + _PADCHAR + _PADCHAR );
        break;

      case 2:
        b10 = ( _getbyte( s, i ) << 16 ) | ( _getbyte( s, i + 1 ) << 8 );
        x.push( _ALPHA.charAt( b10 >> 18 ) + _ALPHA.charAt( ( b10 >> 12 ) & 0x3F ) + _ALPHA.charAt( ( b10 >> 6 ) & 0x3f ) + _PADCHAR );
        break;
    }

    return x.join( "" );
  }


  return {
    decode: _decode,
    encode: _encode,
    VERSION: _VERSION
  };
      
}( jQuery ) );

