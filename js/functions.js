jQuery(document).ready(function () { 
	
	var loginForm = jQuery('#loginform,#registerform,#front-login-form,#setupform');

	login_pass = loginForm.find('p.login-password,p.login');	
		if( login_pass.length !== 0){// *******************LOGIN FORM ON FRONT-END
			login_pass.prepend('<button id=\"wc-login\" class=\"btn-login\" type=\"button\"></button>');		
			input = login_pass.find("input[type=password],input#user_pass,input[name=pwd],input#pass,input#password");		
			if( input.length !== 0){
				padding_top    = parseInt(input.css('padding-top'));
				if (padding_top > 5) {padding_top = padding_top/2; }			
					login_pass.find("button.btn-login").css('top',input.position().top + padding_top );					
					login_pass.find("button.btn-login").css('left',input.position().left + input.innerWidth() - input.outerHeight()*1.2);
					login_pass.find("button.btn-login").css('height',input.innerHeight());

				}
			}
		else{//********************************************LOGIN FORM ON BACK-END
			input = loginForm.find('input[type=password],input#user_pass,input#pass,input#password,input#user_password,input[name=pwd]');
			if( input.length !== 0 ){
				login_pass = input.closest('label,p');
				if( login_pass.length !== 0 ){
					login_pass.prepend('<button id=\"wc-login\" class=\"btn-login\" type=\"button\"></button>');
					padding_top    = parseInt(input.css('padding-top'));
					if (padding_top > 5) {padding_top = padding_top/2; }
						login_pass.find("button.btn-login").css('top',input.position().top + padding_top );
						login_pass.find("button.btn-login").css('left',input.position().left + input.innerWidth() - input.innerHeight());
						login_pass.find("button.btn-login").css('height',input.innerHeight());
					}
				}
			}	
});


jQuery(document).ready(function() {
			jQuery('#wc-login').click(function() {				
				jQuery('input[type=password],input#user_pass,input#pass,input#password,input#user_password,input[name=pwd]').wordcrypt({
					domain_name:     domain,
					password_length: password_length,
					first_char:      thefirstchar,
					special_chars:   specharacters,
					alpha_numeric:   alphanumeric,
					require_upper:   uppercase,
					require_lower:   lowercase,
					require_number:  number,
					require_symbol:  symbol,
					language:  		 language,
				});				
			});
		});

jQuery(document).ready(function() {
	// Enable/Disable the special_chars selector when the alpha_numeric only 
	// option is changed.
	$ = jQuery.noConflict();
	$(document).on('change', '#alphanumeric', function(e) {
		if ($(e.target).is(':checked')) {
			$('#specharacters').attr('disabled', 'disabled');
		} else {
			$('#specharacters').removeAttr('disabled');
		}
	});
});
