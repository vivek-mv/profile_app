
$(document).ready(function(){
	validation.validate();
});

// object to store any errors and call all the validation functions
var validation = {
	noError: true,
	validate: function (){
		//validate text fields
		validateText();
	}
}

//function for text validation
function validateText() {
	var textObjLength = $('.text_input').length;
	$('.text_input').on( "blur", function(){
		
			console.log($(this).parent().children('span').html('hello world'));
		
	}); 
}