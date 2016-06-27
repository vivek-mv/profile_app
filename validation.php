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

            if ( strlen($data) > $length ) {
            return true;
            }
            return false;
        }

        /**
         * Validation for the number fields
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateNumber($data) {

            if (!preg_match("/^[0-9]*$/", $data)) {
                return true;
            }
            return false;
        }

        /**
         * Validation for email field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateEmail($data) {

            if ( !preg_match("/^[a-zA-Z0-9@._]*$/", $data) ) {
                
                return true;
            }
            if ( !filter_var($data, FILTER_VALIDATE_EMAIL) ) {
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

            if ( !empty($data) && strlen($data) != 10 ) {
            return true;
            }
            return false;
        }

        /**
         * Validation for zip field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateZip($data) {

            if ( !empty($data) && strlen($data) != 6 ) {
            return true;
            }
            return false;
        }

        /**
         * Validation for fax field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validateFax($data) {

            if ( !empty($data) && strlen($data) > 15 ) {
            return true;
            }
            return false;
        }

        /**
         * Validation for password field
         *
         * @access public
         * @param String 
         * @return Boolean
         */
        public static function validatePassword($data) {
            
            if ( (!preg_match("/^[a-zA-Z0-9]*$/", $data)) || ($data === '') ) {
            return true;
            }
            return false;
        }
	}
?>