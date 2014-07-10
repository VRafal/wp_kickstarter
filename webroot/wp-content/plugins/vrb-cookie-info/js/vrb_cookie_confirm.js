/**
 * @author: Rafał Bernaczek ak. VRB
 * @email: rafal@bernaczek.pl
 * @date: Date: 20.06.2014
 * @version: 1.0
 */
(function($) {
	cokieConfirm = function(customParams) {
		var params = $.extend({
			timeIn: 100,
			timeOut: 20000,
			appendTo: 'body',
			className: null,

			showClass: 'ccShow',
			intorTextClass: 'ccIntroText',
			fullTextClass: 'ccFullText',

			intorBackClass: 'ccIntroBack',
			fullBackClass: 'ccFullBack',

			messageText: 'Ta strona korzysta z plików cookies. Kontynuując przeglądanie wyrażasz na to zgodę.',
			buttonText: 'Zgadzam się',

			runCookie: true,
			autoSetCookieAccept: false,
			cookieName: 'acceptCookies',
			cookieValue: 'yes',
			cookieExdays: 1000
		}, customParams);

		var timeOut;
		var $cm;

		var init = function() {
			clearTimeout(timeOut);

			$cm = $('<div id="cokieConfirm" class="ccIntroText ccIntroBack"></div>').appendTo(params.appendTo);
			$ccMessage = $('<div class="ccMessage">' + compileText(params.messageText) + '</div>').appendTo($cm);
			$ccMessage.find('a').each(function() {
				var $this = $(this);
				var url = $this.attr('href');
				$this.attr('href', '#showFullPolicyText')
				$this.attr('href', '#cookiePolicy').click(function() {
					clearTimeout(timeOut);
					$ccMessage.fadeOut();
					$cm.removeClass(params.intorBackClass).addClass(params.fullBackClass).animate({
						height: '100%'
					}, function() {
						if ($.scrollTo) $(window).scrollTo(0);

						$cm.attr('style', '').removeClass(params.showClass).removeClass(params.intorTextClass).addClass(params.fullTextClass).css({
							height: $('body').height()
						});

						$.ajax({
							type: "GET",
							url: url,
							cache: false,
							success: function(msg) {
								$ccMessage.html(msg);
								$ccMessage.fadeIn();
								$cm.addClass(params.apsolutePositionClass);
							}
						});

					});
				});
			});
			$('<div class="ccButton"><p>' + params.buttonText + '</p></div>').appendTo($cm).click(function() {
				setAndClose();
			});

			if (params.className != null) $cm.addClass(params.className);

			timeOut = setTimeout(show, params.timeIn);
		};

		var compileText = function(txt) {
			var entityMap = {
				"&amp;": "&",
				"&lt;": "<",
				"&gt;": ">",
				'&quot;': '"',
				'&#39;': "'",
				'&#x2F;': "/"
			};
			for ( var key in entityMap) {
				txt = txt.replace(RegExp(key, "g"), entityMap[key]);
			}
			return txt;
		};

		var show = function() {
			clearTimeout(timeOut);
			$cm.addClass(params.showClass);
			if (params.timeOut > 0) timeOut = params.autoSetCookieAccept?setTimeout(setAndClose, params.timeOut):setTimeout(close, params.timeOut);
		};

		var setAndClose = function() {
			if (params.runCookie) setCookie(params.cookieName, params.cookieValue, params.cookieExdays);
			close();
		};

		var close = function() {
			clearTimeout(timeOut);
			if ($cm.hasClass(params.fullTextClass)) {
				$cm.fadeOut(function() {
					if ($.scrollTo) $(window).scrollTo(0);
					$cm.remove();
				});
			}
			else $cm.removeClass(params.showClass);
		};

		var setCookie = function(c_name, value, exdays) {
			var exdate = new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value = escape(value) + ((exdays == null)?"":"; expires=" + exdate.toUTCString()) + "; path=/";
			document.cookie = c_name + "=" + c_value;
		};

		var getCookie = function(c_name) {
			var i, x, y, ARRcookies = document.cookie.split(";");
			for (i = 0; i < ARRcookies.length; i++) {
				x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
				y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
				x = x.replace(/^\s+|\s+$/g, "");
				if (x == c_name) {
					return unescape(y);
				}
			}
		};

		if (getCookie(params.cookieName) != params.cookieValue || !params.runCookie) {
			$(document).ready(function() {
				init();
			});
		}
	};

})(jQuery);