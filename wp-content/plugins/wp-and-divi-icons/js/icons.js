/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder by Elegant Themes, licensed GPLv2, used under GPLv3 in this project by special permission (see ../license.txt).
*/

jQuery(document).ready(function($) {
	$('.et-pb-icon:not([data-icon]):contains(agsdi-),.et-pb-icon:not([data-icon]):contains(agsdix-)').each(function() {
		var iconId = $(this).text();
		if (iconId.substr(0,6) == 'agsdi-' || iconId.substr(0,7) == 'agsdix-') {
			$(this).attr('data-icon', iconId).html('');
		}
	});
	
	if (!agsdi_Modernizr.cssmask) {
		console.log('Divi Icon Expansion Pack: Will try to load fallback method due to lack of support for CSS Masks');
		$('<script>').attr('src', ags_divi_icons_config.pluginDirUrl + '/js/fallback.js').appendTo('head:first');
	}
	
});


// Modernizr (modified)
/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-cssmask !*/
!function(e,n,t){function r(e,n){return typeof e===n}function o(){var e,n,t,o,s,i,l;for(var a in g)if(g.hasOwnProperty(a)){if(e=[],n=g[a],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(o=r(n.fn,"function")?n.fn():n.fn,s=0;s<e.length;s++)i=e[s],l=i.split("."),1===l.length?Modernizr[l[0]]=o:(!Modernizr[l[0]]||Modernizr[l[0]]instanceof Boolean||(Modernizr[l[0]]=new Boolean(Modernizr[l[0]])),Modernizr[l[0]][l[1]]=o),S.push((o?"":"no-")+l.join("-"))}}function s(e,n){return!!~(""+e).indexOf(n)}function i(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function l(e,n){return function(){return e.apply(n,arguments)}}function a(e,n,t){var o;for(var s in e)if(e[s]in n)return t===!1?e[s]:(o=n[e[s]],r(o,"function")?l(o,t||n):o);return!1}function u(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function f(n,t,r){var o;if("getComputedStyle"in e){o=getComputedStyle.call(e,n,t);var s=e.console;if(null!==o)r&&(o=o.getPropertyValue(r));else if(s){var i=s.error?"error":"log";s[i].call(s,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}}else o=!t&&n.currentStyle&&n.currentStyle[r];return o}function d(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):P?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function p(){var e=n.body;return e||(e=d(P?"svg":"body"),e.fake=!0),e}function c(e,t,r,o){var s,i,l,a,u="modernizr",f=d("div"),c=p();if(parseInt(r,10))for(;r--;)l=d("div"),l.id=o?o[r]:u+(r+1),f.appendChild(l);return s=d("style"),s.type="text/css",s.id="s"+u,(c.fake?c:f).appendChild(s),c.appendChild(f),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(n.createTextNode(e)),f.id=u,c.fake&&(c.style.background="",c.style.overflow="hidden",a=z.style.overflow,z.style.overflow="hidden",z.appendChild(c)),i=t(f,e),c.fake?(c.parentNode.removeChild(c),z.style.overflow=a,z.offsetHeight):f.parentNode.removeChild(f),!!i}function m(n,r){var o=n.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(u(n[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var s=[];o--;)s.push("("+u(n[o])+":"+r+")");return s=s.join(" or "),c("@supports ("+s+") { #modernizr { position: absolute; } }",function(e){return"absolute"==f(e,null,"position")})}return t}function y(e,n,o,l){function a(){f&&(delete E.style,delete E.modElem)}if(l=r(l,"undefined")?!1:l,!r(o,"undefined")){var u=m(e,o);if(!r(u,"undefined"))return u}for(var f,p,c,y,h,v=["modernizr","tspan","samp"];!E.style&&v.length;)f=!0,E.modElem=d(v.shift()),E.style=E.modElem.style;for(c=e.length,p=0;c>p;p++)if(y=e[p],h=E.style[y],s(y,"-")&&(y=i(y)),E.style[y]!==t){if(l||r(o,"undefined"))return a(),"pfx"==n?y:!0;try{E.style[y]=o}catch(g){}if(E.style[y]!=h)return a(),"pfx"==n?y:!0}return a(),!1}function h(e,n,t,o,s){var i=e.charAt(0).toUpperCase()+e.slice(1),l=(e+" "+_.join(i+" ")+i).split(" ");return r(n,"string")||r(n,"undefined")?y(l,n,o,s):(l=(e+" "+x.join(i+" ")+i).split(" "),a(l,n,t))}function v(e,n,r){return h(e,t,t,n,r)}var g=[],C={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){g.push({name:e,fn:n,options:t})},addAsyncTest:function(e){g.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=C,Modernizr=new Modernizr;var S=[],w="Moz O ms Webkit",_=C._config.usePrefixes?w.split(" "):[];C._cssomPrefixes=_;var x=C._config.usePrefixes?w.toLowerCase().split(" "):[];C._domPrefixes=x;var z=n.documentElement,P="svg"===z.nodeName.toLowerCase(),b={elem:d("modernizr")};Modernizr._q.push(function(){delete b.elem});var E={style:b.elem.style};Modernizr._q.unshift(function(){delete E.style}),C.testAllProps=h,C.testAllProps=v,Modernizr.addTest("cssmask",v("maskRepeat","repeat-x",!0)),o(),delete C.addTest,delete C.addAsyncTest;for(var k=0;k<Modernizr._q.length;k++)Modernizr._q[k]();e.agsdi_Modernizr=Modernizr}(window,document);
// End Modernizr