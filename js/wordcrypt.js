(function($) {	
	$.fn.wordcrypt = function(options) {
		var element = $(this);

		// Merge default password options:
		options = $.extend({
			password_length: 16,
			first_char: 'random',
			special_chars: 'include_any',
			alpha_numeric: '0',
			require_upper: '0',
			require_lower: '0',
			require_number: '0',
			require_symbol: '0'
		}, options);		

		// Encode option values:
		var domain_name     = encodeURIComponent(options.domain_name);
		var password_length = encodeURIComponent(options.password_length);
		var first_char      = encodeURIComponent(options.first_char);
		var special_chars   = encodeURIComponent(options.special_chars);
		var alpha_numeric   = encodeURIComponent(options.alpha_numeric);
		var require_upper   = encodeURIComponent(options.require_upper);
		var require_lower   = encodeURIComponent(options.require_lower);
		var require_number  = encodeURIComponent(options.require_number);
		var require_symbol  = encodeURIComponent(options.require_symbol);
		var language  = encodeURIComponent(options.language);

		// Determine the URL from which the SDK was included:
		var prefix = "https://app.wordcrypt.com";//$('script[src$="wordcrypt.js"]').attr('src').replace('/js/wordcrypt.js', '');

		// Generate the SSL popup URL:
		var url = prefix + '/ssl' +
			'?domain_name=' + domain_name +
			'&password_length=' + password_length + 
			'&first_char=' + first_char + 
			'&special_chars=' + special_chars + 
			'&alpha_numeric=' + alpha_numeric + 
			'&require_upper=' + require_upper +
			'&require_lower=' + require_lower +
			'&require_number=' + require_number +
			'&require_symbol=' + require_symbol +
			'&lan=' + language;

		// Open the SSL popup.
		var win = window.open(url, 'popupWindow', 'width=340,height=655,scrollbars=yes');

		// Because some browsers handle messaging differently, we must first determine 
		// which method and event name to use.
		var eventMethod  = window.addEventListener ? 'addEventListener' : 'attachEvent';
		var eventer      = window[eventMethod];
		var messageEvent = eventMethod == 'attachEvent' ? 'onmessage' : 'message';

		// Handle the returned password from the SSL popup.
		eventer(messageEvent, function(e) {
			if (e.data.password) {	
				element.val(e.data.password);
				win.close();
			}
		});	
				
	};
})(jQuery);