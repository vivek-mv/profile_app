<?php

/**
 * Check the permissions of the logged in user
 * @access public
 * @package void
 * @subpackage void
 * @category void
 * @author vivek
 * @link void
 */
Class CheckPermissions {
    /**
     * Check whether the user is allowed or not
     *
     * @access public
     * @param String
     * @param String
     * @return Boolean
     */
    public function isAllowed($resource, $action) {
        $allowed = false;
        if ( isset($_SESSION['userPermissions']) ) {
            foreach ($_SESSION['userPermissions'] as $userPermission) {
                if ( ($userPermission['resName'] == $resource) && ($userPermission['pName'] == $action) ) {
                    $allowed = true;
                }
            }
        }

        return $allowed;
    }
}
?>