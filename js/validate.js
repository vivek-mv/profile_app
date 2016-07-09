
// object to store any errors and call all the validation functions
var validation = {
    noError: true,
    /**
     * Calls all the validation functions
     * @param void
     * @retrun void
     */
    validate: function (){

        //validate text fields
        validateText();

        //validate number fields
        validateNumber();

        //validate email field
        validateEmail();

        //validate password field
        validatePassword();

        //validate street field
        validateStreet();

        //validate note field
        validateNote();

    }
}

$(document).ready(function(){

    //Register events for validation
    validation.validate();

    //Register event for reset button
    $("#reset").click(function() {

        $("#form").trigger("reset");

        $.each($( ".error" ), function() {
            $(this).text('');
        });
    });

    //Register event for email checking
    $('#email').on("blur", function () {
        checkEmail();
    });
});

 /**
 * Validates text fields
 * @param void
 * @retrun void
 */
function validateText() {

    $('.text_input').on( "blur keyup ", function(){
        //set the error text to empty string
        $(this).parent().children('span').text('');
        validation.noError = true;

        if ( $(this)[0].value.length > constants.textInputLength ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only "+constants.textInputLength+" characters  allowed");
        }

        if ( !(/^[a-zA-Z ]*$/.test($(this)[0].value) ) ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only charaters and white spaces allowed");
        }
    });
}

/**
 * Validates number fields
 * @param void
 * @retrun void
 */
function validateNumber() {

    $('.numbers').on( "blur keyup", function(){
        //set the error text to empty string
        $(this).parent().children('span').text('');
        validation.noError = true;

        if ( !(/^[0-9]*$/.test($(this)[0].value) ) ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only numbers are allowed");
        } else if ( $(this)[0].value !== '' ) {

                //validation for phone numbers
            if ( (($(this)[0].name === 'mobile') || ($(this)[0].name === 'landline')) &&  ($(this)[0].value.length !== constants.mobileLength) ) {

                validation.noError = false;
                $(this).parent().children('span').text("You must enter "+constants.mobileLength+" digits");

            } else if ( (($(this)[0].name === 'residenceZip') || ($(this)[0].name === 'officeZip')) &&  ($(this)[0].value.length !== constants.zipLength) ) {

                //validation for zip numbers
                validation.noError = false;
                $(this).parent().children('span').text("You must enter "+constants.zipLength+" digits");

            } else if ( (($(this)[0].name === 'residenceFax') || ($(this)[0].name === 'officeFax')) &&  ($(this)[0].value.length !== constants.faxLength )	 ) {

                //validation for fax numbers
                validation.noError = false;
                $(this).parent().children('span').text("You must enter "+constants.faxLength+" digits");
            }
        }
    });
}

/**
 * Validates email field
 * @param void
 * @retrun void
 */
function validateEmail() {

    var checkEmail = /^[a-zA-Z0-9@._-]*$/;
    var email = $('.email');
    email.on( "blur keyup", function(){
        //set the error text to empty string
        $(this).parent().children('span').text('');
        validation.noError = true;
        var atpos = email[0].value.indexOf("@");
        var dotpos = email[0].value.lastIndexOf(".");

        if ( $(this)[0].value === '' ) {
            $(this).parent().children('span').text('');
            validation.noError = true;

        } else if ( !checkEmail.test($(this)[0].value) || ( (atpos<1) || (dotpos<atpos+2) || (dotpos+2 >= $(this)[0].value.length) ) ) {

            validation.noError = false;
            $(this).parent().children('span').text("Invalid email");

        } else if ( $(this)[0].value.length > constants.emailLength ) {

            validation.noError = false;
            $(this).parent().children('span').text("Email should be less than " + constants.emailLength + " charaters");
        }
    });
}

/**
 * Validates password field
 * @param void
 * @retrun void
 */
function validatePassword() { 
    var checkPassword = /^[a-zA-Z0-9]*$/ ;
    $('.password').on("blur keyup", function(){
        //set the error text to empty string
        $('.password').parent().children('span').text('');
        validation.noError = true;

        if ( !checkPassword.test($('.password')[0].value) ) {
            validation.noError = false;
            $('.password').parent().children('span').text("Only letters and numbers are allowed");

        } else if ( $('.password')[0].value.length > constants.passwordLength ) {
            validation.noError = false;
            $('.password').parent().children('span').text("Only " + constants.passwordLength + " charaters allowed");

        }else if ( ($('.password')[0].value !== $('.password')[1].value)) {
            validation.noError = false;
            $('.password').parent().children('span').text("Passwords dont match ");
        }

    });
}

/**
 * Validates street fields
 * @param void
 * @retrun void
 */
function validateStreet() {

    $('.street').on( "blur keyup ", function(){
        //set the error text to empty string
        $(this).parent().children('span').text('');
        validation.noError = true;

        if ( $(this)[0].value.length > constants.streetLength ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only "+constants.streetLength+" characters  allowed");
        }

        if ( !(/^[a-zA-Z0-9()\/\- ]*$/.test($(this)[0].value) ) ) {
            //search for escaping slashes
            validation.noError = false;
            $(this).parent().children('span').text("Only these a-zA-Z0-9()/- are allowed");
        }
    });
}

/**
 * Validates note fields
 * @param void
 * @retrun void
 */
function validateNote() {

    $('.note').on( "blur keyup ", function(){
        //set the error text to empty string
        $(this).parent().children('span').text('');
        validation.noError = true;

        if ( $(this)[0].value.length > constants.noteLength ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only "+constants.noteLength+" characters  allowed");
        }

        if ( !(/^[a-zA-Z0-9@#!*()&\n\"\' ]*$/.test($(this)[0].value) ) ) {

            validation.noError = false;
            $(this).parent().children('span').text("Only these a-zA-Z0-9@#!*()\"\'& are allowed");
        }
    });
}

/**
 * check for required fields on submit
 * @param void
 * @retrun void
 */
function checkRequired() {

    $.each($( ".required" ), function() {
        if ( $(this)[0].value === '' ) {
            $(this).parent().children('span').text("This field is required");
            validation.noError = false;

        }
    });
}

/**
 * check whether email already exits or not
 * @param void
 * @retrun void
 */
function checkEmail() {
    var mail = $('#email').val();
    if ( !(mail === '') ) {
        $.ajax({
            url: 'dbOperations.php',
            data: {
                data: mail
            },
            type: "POST",
            success: function (response) {
                if ( response == '1') {
                    $('#email').parent().children('span').text('Email already taken, Please try another');
                }
            }
        });
    }

}


