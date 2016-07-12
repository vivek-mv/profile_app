<?php

/**
 * Access Control list class to get all the user permission as per role of the user
 * @access public
 * @package void
 * @subpackage void
 * @category void
 * @author vivek
 * @link void
 */

Class Acl {
    /**
     * Get the user permissions
     *
     * @access public
     * @param String
     * @return void
     */
    public function getResourcePermission($roleId) {
        $sql = "SELECT res.resource_name AS resName, p.permission_name AS pName
                FROM role_resource_permission AS rrp
                JOIN resource AS res ON rrp.resource_id = res.resource_id
                JOIN permission AS p ON rrp.permission_id = p.permission_id
                WHERE rrp.role_id = $roleId";
        
        $dbOperations = new DbOperations();
        $data = $dbOperations->executeSql($sql);


        while($row = $data->fetch_assoc()){
            $_SESSION['userPermissions'][] = $row;

        }
    }
}

?>