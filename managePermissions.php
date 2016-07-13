<?php
    if ( !(isset($_POST['ajax']) && $_POST['ajax'] == 1) ) {
        header("Location:index.php");
        exit();
    }
    session_start();
    //Check for user's session
    if ( !isset($_SESSION['employeeId']) ) {
        echo '{"error" : "1", "error_msg" : "Unauthorized", "error_code" : "401"}';
        exit();
    }

    require_once('dbOperations.php');

    require_once('checkPermissions.php');

    //Enable error reporting
    ini_set('error_reporting', E_ALL);

    /**
     * Gets and sets the permissions
     * @access public
     * @package void
     * @subpackage void
     * @category void
     * @author vivek
     * @link void
     */

    Class ManagePermissions {

        /**
         * Gets the available roles, resources and permissions,and sends as json
         *
         * @access public
         * @param void
         * @return void
         */
        public function getRoleResourcePermissions() {

            $dbOperations = new DbOperations();

            $roles = "SELECT role.role_id, role.role_name
                FROM role";

            $resource = "SELECT resource.resource_id, resource.resource_name
                FROM resource";

            $permissions = "SELECT permission.permission_id, permission.permission_name
                FROM permission";

            $rrp = "SELECT role_id, resource_id, permission_id 
                FROM role_resource_permission";

            $getRoles = $dbOperations->executeSql($roles);
            $getResource = $dbOperations->executeSql($resource);
            $getPermission = $dbOperations->executeSql($permissions);
            $getRRP = $dbOperations->executeSql($rrp);
            $json = [];

            while($row = $getRoles->fetch_assoc()){
                $json['role'][] = $row;
            }

            while($row = $getResource->fetch_assoc()){
                $json['resource'][] = $row;
            }

            while($row = $getPermission->fetch_assoc()){
                $json['permission'][] = $row;
            }

            while($row = $getRRP->fetch_assoc()){
                $json['rrp'][] = $row;
            }

            print_r(json_encode($json));
        }

        /**
         * Sets the roles, resources and permissions
         *
         * @access public
         * @param String
         * @param String
         * @param String
         * @param String
         * @return void
         */

        public function setRoleResourcePermissions($roleId, $resourceId, $permissionId, $action) {
            $dbOperations = new DbOperations();
            if ( $action == 'add' ) {
                $sql = "SELECT * 
                  FROM role_resource_permission
                  WHERE role_id = $roleId AND resource_id = $resourceId AND permission_id = $permissionId";
                $getRow = $dbOperations->executeSql($sql);
                $rowcount=mysqli_num_rows($getRow);

                if ( $rowcount == 0 ) {
                    $sql = "INSERT INTO role_resource_permission (role_id,resource_id,permission_id)
                        VALUES($roleId,$resourceId,$permissionId)";
                    $getRow = $dbOperations->executeSql($sql);

                }
            } else if ( $action == 'delete' ) {
                $sql = "DELETE FROM role_resource_permission
                    WHERE role_id = $roleId AND resource_id = $resourceId AND permission_id = $permissionId";

                $getRow = $dbOperations->executeSql($sql);
            }

        }
    }
$mp = new ManagePermissions();
if ( isset($_POST['code']) && $_POST['code'] == '1' ) {
    $mp->getRoleResourcePermissions();
}

if ( isset($_POST['code']) && $_POST['code'] == '2' ) {
    $mp->setRoleResourcePermissions($_POST['roleId'], $_POST['resourceId'], $_POST['permissionId'], $_POST['action']);
}

?>