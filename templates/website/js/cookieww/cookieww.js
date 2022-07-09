/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}(function(e){function n(e){return e}function o(e){return decodeURIComponent(e.replace(t," "))}function i(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return r.json?JSON.parse(e):e}catch(n){}}var t=/\+/g,r=e.cookie=function(t,c,a){if(void 0!==c){if(a=e.extend({},r.defaults,a),"number"==typeof a.expires){var u=a.expires,f=a.expires=new Date;f.setDate(f.getDate()+u)}return c=r.json?JSON.stringify(c):String(c),document.cookie=[r.raw?t:encodeURIComponent(t),"=",r.raw?c:encodeURIComponent(c),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":""].join("")}for(var d=r.raw?n:o,p=document.cookie.split("; "),s=t?void 0:{},m=0,x=p.length;x>m;m++){var l=p[m].split("="),g=d(l.shift()),v=d(l.join("="));if(t&&t===g){s=i(v);break}t||(s[g]=i(v))}return s};r.defaults={},e.removeCookie=function(n,o){return void 0!==e.cookie(n)?(e.cookie(n,"",e.extend({},o,{expires:-1})),!0):!1}});

/*!
 * jQuery Cookie Information Popup
 * Copyright 2016 Grzegorz Miskiewicz
 * Released under the MIT license
 */
(function ($) {
	$(document).ready(function() {
		$(this).cmsciw();
	});

	$.fn.cmsciw = function( options ) {
    	var defaults = {
	    	text : 'Na naszych stronach internetowych stosujemy pliki cookies. Korzystając z naszych serwisów internetowych bez zmiany ustawień przeglądarki wyrażasz zgodę na stosowanie plików cookies.',
	    	button: '<span class="fa fa-check fa-2x"></span>',
	    	cookie_expire: 30
	    	
    	}
    	
    	var o = $.extend(defaults, options);
    	var isCookieExists = $.cookie("cww");
    	if(!isCookieExists) {
    	
	    	var template = '<div id="cwwb">'
	    	+ '<div class="text">' + o.text + '</div>'
	    	+ '<div class="button"><button type="button" id="cwwb-btn-ok">' + o.button + '</button></div>'
	    	+ '</div>';
	    	
	    	$("body").append( template );
	    	
	    	$("button#cwwb-btn-ok").click( function() {
				$("div#cwwb").hide().remove();
				$.cookie("cww", 1, { expires : o.cookie_expire });
			});	
    	}
	}
})(jQuery); 

