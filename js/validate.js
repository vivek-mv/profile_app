
function validateForm() {
	
	//Add reset functionality

	//validate text field for only charaters and white spaces
	var error = 0;
	var elements = document.getElementsByClassName('text_input');
	for(var i = 0; i < elements.length; i++) {
		elements[i].parentElement.firstElementChild.innerHTML = '';
		
		if ( elements[i].value.length > constants.textInputLength ) {
			error++;
			elements[i].parentElement.firstElementChild.innerHTML = "Only "+constants.textInputLength+" characters  allowed";
		}

		if ( !(/^[a-zA-Z ]*$/.test(elements[i].value) ) ) {
			
			error++;
			elements[i].parentElement.firstElementChild.innerHTML = "Only charaters and white spaces allowed";
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
				if ( numbers[i].value != '' ) {
				error++;
				}
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter "+constants.mobileLength+" digits";
			}

			//check for residence zip and office zip
			if ( ((numbers[i].name == 'residenceZip') || (numbers[i].name == 'officeZip')) &&  (numbers[i].value.length != constants.zipLength) ) {
				
				if ( numbers[i].value != '' ) {
				error++;
				}
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter "+constants.zipLength+" digits";
			}

			//check for residence fax and office fax
			if ( ((numbers[i].name == 'residenceFax') || (numbers[i].name == 'officeFax')) &&  (numbers[i].value.length != constants.faxLength ) ) {
				
				if ( numbers[i].value != '' ) {
				error++;
				}
				numbers[i].parentElement.firstElementChild.innerHTML = "You must enter "+constants.faxLength+" digits";
			}

			//when user didnot entered anything then dont set any error messages
			if ( numbers[i].value == '' ) {
				numbers[i].parentElement.firstElementChild.innerHTML = '';
			}
			
		}
	}
	
	//validate email
	var email = document.getElementById('email');
	email.parentElement.firstElementChild.innerHTML = '';
	
	error += validateEmail(email);

    //validate password
    var password = document.getElementsByClassName('password');
    password[0].parentElement.firstElementChild.innerHTML = '';
    
    error += validatePassword(password);

    if ( password[0].value !== password[1].value ) {
    	password[0].parentElement.firstElementChild.innerHTML = 'Passwords dont match';
    }
	//Validate Street 
	var street = document.getElementsByClassName('street');
	for ( i = 0; i<street.length; i++ ) {
		street[i].parentElement.firstElementChild.innerHTML = '';
		if ( street[i].value.length > constants.streetLength ) {
			error++;
			street[i].parentElement.firstElementChild.innerHTML = "Only "+constants.streetLength+" charaters allowed";
		}
	}

	//validate note
	var note = document.getElementById('note');
	note.parentElement.firstElementChild.innerHTML = '';
	if ( note.value.length > constants.noteLength ) {
		error++;
		note.parentElement.firstElementChild.innerHTML = "Only "+constants.noteLength+" charaters allowed";
	}

	//check for required fields
	if ( (elements[0].value == '') ) {
		error++;
		elements[0].parentElement.firstElementChild.innerHTML = "This field is required";
	}
	if ( email.value == '' ) {
		error++;
		email.parentElement.firstElementChild.innerHTML = "This field is required";
	}
	if ( password[0].value == '' ) {
		error++;
		password[0].parentElement.firstElementChild.innerHTML = "This field is required";
	}

	//Check for error and return accordingly
	if ( error > 0 ) {
		return false;
	} else {
		return true;
	}
}

// function to validate email
function validateEmail(email) {
	var checkEmail = /^[a-zA-Z0-9@._-]*$/;
    if ( !checkEmail.test(email.value) ) {
		email.parentElement.firstElementChild.innerHTML = "Invalid email";
		return 1;
    }

    if ( email.value.length > constants.emailLength ) {
		email.parentElement.firstElementChild.innerHTML = "Email should be less than " + constants.emailLength + " charaters";
		return 1;
    }

    var atpos = email.value.indexOf("@");
    var dotpos = email.value.lastIndexOf(".");
    if ( (atpos<1) || (dotpos<atpos+2) || (dotpos+2 >= email.value.length) ) {
		email.parentElement.firstElementChild.innerHTML = "Invalid email";
		return 1;
    }
    return 0;
}
// function for validationg password
function validatePassword(password) {

	var checkPassword = /^[a-zA-Z0-9]*$/ ;
	if ( !checkPassword.test(password[0].value) ) {
		password[0].parentElement.firstElementChild.innerHTML = "Only letters and numbers are allowed";
		return 1;
	}

	if ( password[0].value.length > constants.passwordLength ) {
		password[0].parentElement.firstElementChild.innerHTML = "Only " + constants.passwordLength + " charaters allowed";
		return 1;
	}
	return 0;
	
}

//function for validating only email and password (to be accessed from login.php file)
function validateEmailPassword() {

	var error = 0;
	var email = document.getElementById('email');
	var password = document.getElementsByClassName('password');

	//set the error messages to empty string
	email.parentElement.firstElementChild.innerHTML = '';
	password[0].parentElement.firstElementChild.innerHTML = '';

	error += validateEmail(email);
	
	error += validatePassword(password);

	if ( email.value == '' ) {
		error++;
		email.parentElement.firstElementChild.innerHTML = "This field is required";
	}

	if ( password[0].value == '' ) {
		error++;
		password[0].parentElement.firstElementChild.innerHTML = "This field is required";
	}
	
	if ( error > 0 ) {
		return false;
	} else {
		return true;
	}
}