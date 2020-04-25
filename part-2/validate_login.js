$(document).ready(function() {
    $.validator.addMethod("regx", function(value, element, regexpr) {  
        
    return regexpr.test(value);
}, "Invalid Characters Found.");
    
    
    $('#login').validate({
        
        onkeyup: false,
        onfocusout: false,
        rules: {
            l_username: {
                required: true,
                minlength: 5,
                regx: /^[a-zA-Z0-9]+$/ 
            },
            l_password: {
                required: true,
                minlength: 5,
                regx: /^[a-zA-Z0-9]+$/ 
            }
        },
       
			
	
});
});
