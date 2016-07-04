<?php 

    /**
     * Performs validation for the form input fields.
     * @access public
     * @package void
     * @subpackage void
     * @category void
     * @author vivek
     * @link void
     */
	Class Validation {

		/**
         * Filters the data from form input fields
         *
         * @access public
         * @param String $data
         * @return String
         */
        public static function getCorrectData($data) {

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        /**
         * Validates the select fields
         *
         * @access public
         * @param String
         * @param String
         * @param Array 
         * @return Boolean
         */
        public static function validateSelect($data, $type, $states = array() ) {

            if ( ($type == 'prefix') && ($data != 'mr') && ($data != 'mis') ) {
                return true;
            }

            if ( ($type == 'states') && ($data != '') && !(in_array($data, $states)) ) {
                return true;
            }
            return false;
        }

        /**
         * Validates the radio fields
         *
         * @access public
         * @param String
         * @param String 
         * @return Boolean
         */
        public static function validateRadio($data, $type ) {

            if ( ($type == 'gender') && !(in_array($data, array('m', 'f', 'o'))) ) {
                return true;
            }

            if ( ($type == 'mStatus') && !(in_array($data, array('married', 'unmarried'))) ) {
                return true;
            }

            if ( ($type == 'employment') && !(in_array($data, array('employed', 'unemployed'))) ) {
                return true;
            }

            return false;
        }

        /**
         * Validates the checkbox fields
         *
         * @access public
         * @param Array
         * @return Boolean
         */
        public static function validateCheckbox($data) {

            foreach ($data as $value) {
                if ( !(in_array($value, array('mail', 'msg', 'phone', 'any'))) ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Validates the text field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateText($data) {

        	if ( !preg_match("/^[a-zA-Z ]*$/", $data) ) {
                return false;
        	}
        	return true;
        }

        /**
         * Validation for the number of characters in a form field
         *
         * @access public
         * @param String 
         * @param Integer 
         * @return Boolean
         */
        public static function validateLength($data, $length) {

            return ( strlen($data) > $length );
        }

        /**
         * Validation for the number fields
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateNumber($data) {

            return (!preg_match("/^[0-9]*$/", $data));
        }

        /**
         * Validation for email field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateEmail($data) {

            if ( !($data == '') && (!preg_match("/^[a-zA-Z0-9@._]*$/", $data)) 
                && (!filter_var($data, FILTER_VALIDATE_EMAIL)) ) {
                
                return true;
            }
            return false;
        }

        /**
         * Validation for phone field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validatePhone($data) {

            return ( !empty($data) && strlen($data) != 10 );
        }

        /**
         * Validation for zip field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateZip($data) {

            return ( !empty($data) && strlen($data) != 6 );
        }

        /**
         * Validation for fax field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateFax($data) {

            return ( !empty($data) && strlen($data) > 15 );
        }

        /**
         * Validation for password field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validatePassword($data) {
            
            return ( !preg_match("/^[a-zA-Z0-9]*$/", $data) );
        }
	}
?>