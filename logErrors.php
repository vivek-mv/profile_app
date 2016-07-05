<?php
    /**
     * Writes the error to the log file.
     * @param String
     * @retrun void
     */
    function logError($errorData) {

        $errorData = $errorData . ' ( Error logged on - ' . date('m/d/Y h:i:s a', time()) . " )\n";

        // Write the contents to the file,
        // using the FILE_APPEND flag to append the content to the end of the file
        // and the LOCK_EX flag to prevent anyone else writing to the file at the same time

        file_put_contents(ERROR_LOG_PATH, $errorData,FILE_APPEND | LOCK_EX);

    }

?>