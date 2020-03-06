$(document).ready(function() {
    $.validator.addMethod("regx", function(value, element, regexpr) {          
    return regexpr.test(value);
}, "Invalid Characters Found.");
    
    
    $('#task').validate({
        
        onkeyup: false,
        onfocusout: false,
        rules: {
            task_name: {
                required: true,
                minlength: 2,
                regx: /^[a-zA-Z0-9\s]+$/ 
            },
            task_location: {
                required: true,
                minlength: 4,
                regx: /^[a-zA-Z0-9\s]+$/ 
                
            },
             rate_due: {
                required: true,
                date: true,
                dateFormat: true
                
                
            }
         
           
        }
        
		},
        submitHandler: function(form) {
    // do other things for a valid form
    form.submit();
			
	
});
});
