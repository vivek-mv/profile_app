
function validateForm() {
	// Check for the required fields
	//Add reset functionality

	//validate text field for only charaters and white spaces
	var error = 0;
	var elements = document.getElementsByClassName('text_input');
	for(var i = 0; i < elements.length; i++) {

		if ( !(/^[a-zA-Z ]*$/.test(elements[i].value) ) ) {
			
			error++;
			elements[i].parentElement.firstElementChild.innerHTML = "Only charaters and white spaces allowed";
		} else {

			elements[i].parentElement.firstElementChild.innerHTML = '';
		}
	}

	//validate for numbers only fields
	var numbers = document.getElementsByClassName('number');
	for(i = 0; i < numbers.length; i++) {

		numbers[i].parentElement.firstElementChild.innerHTML = '';
		if ( !(/^[0-9]*$/.test(numbers[i].value) ) ) {
			
			error++;
			numbers[i].parentElement.firstElementChild.innerHTML = "Only numbers allowed";
		} else {
			//check for mobile and landline length
			if ( ((numbers[i].name == 'mobile') || (numbers[i].name == 'landline')) &&  (numbers[i].value.length != constants.mobileLength) ) {
				
				error++;
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter 10 digits";
			}

			//check for residence zip and office zip
			if ( ((numbers[i].name == 'residenceZip') || (numbers[i].name == 'officeZip')) &&  (numbers[i].value.length != constants.zipLength) ) {
				
				error++;
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter 6 digits";
			}

			//check for residence fax and office fax
			if ( ((numbers[i].name == 'residenceFax') || (numbers[i].name == 'officeFax')) &&  (numbers[i].value.length != constants.faxLength ) ) {
				
				error++;
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter 10 digits";
			}

			//when user didnot entered anything then dont set any error messages
			if ( numbers[i].value == '' ) {
				numbers[i].parentElement.firstElementChild.innerHTML = '';
			}
			
		}
	}

	//validate email
	var email = document.getElementById('email');
	var checkEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    console.log(checkEmail.test(email.value));
	//Check for error and return accordingly
	return false;
}