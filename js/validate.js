
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
	//Check for error and return accordingly
	return false;
}