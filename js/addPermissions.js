var checkboxResponseObj;
/**
 * Get the role,resource and permissions data
 * @param void
 * @retrun void
 */
function display() {
    //send ajax request to get the role, resource and permissions data
    $.ajax({
        url: 'managePermissions.php',
        data: {
            code: 1,
            ajax: 1
        },
        dataType : 'json',
        type: "POST",
        success: function (response) {
            if ( response.error_code === '401') {
                alert('You are logged out. Please login again ');
                location.reload();
            }else {
                displayRRP(response);
                selectCheckbox(response.rrp);
                checkboxResponseObj = response.rrp;

            }

        }
    });
}


/**
 * Selects the checkbox based on role and resource
 * @param object
 * @retrun void
 */
function selectCheckbox(rrpObj) {

    var getRoleId = $('#displayRole').val();
    var getResourceId = $('#displayResources').val();
    $.each($('.checkbox'),function () {
        $(this).prop('checked', false);
    });
    $.each($('.checkbox'),function () {
        var checkbox = this;
        var getPermissionId = this.value;
        $.each(rrpObj,function () {
            if ( (this.role_id == getRoleId)  && (this.resource_id == getResourceId) && (this.permission_id == getPermissionId) ) {
                $(checkbox).prop('checked', true);
            }
        });

    });
}

/**
 * Send the role,resource and permissions data
 * @param void
 * @retrun void
 */
function sendPermissions(checkboxObj,isChecked) {
    var getRoleId = $('#displayRole').val();
    var getResourceId = $('#displayResources').val();
    var getPermissionId = checkboxObj.value;
    var action = 'delete';
    if ( isChecked ) {
        action = 'add';
    }

    //send ajax request to set the role, resource and permissions data
    $.ajax({
        url: 'managePermissions.php',
        data: {
            code: 2,
            action:action,
            roleId:getRoleId,
            resourceId:getResourceId,
            permissionId:getPermissionId,
            ajax: 1
        },
        dataType : 'json',
        type: "POST",
        success: function (response) {
            if ( response.error_code === '401') {
                alert('You are logged out. Please login again ');
                location.reload();
            }
        }
    });
}

/**
 * Display the role,resource and permissions data
 * @param json
 * @retrun void
 */
function displayRRP(response) {


    $('.row').append('<div class="panel panel-default" style="width: 70%;"><div class="panel-heading" style="text-align: center;">ADMIN PANEL</div><div class="panel-body dashboard"></div></div>');
    $('.dashboard').append('<div id="formdiv" class="form-inline"></div>');
    $('#formdiv').append(' Role : <select id="displayRole" class="form-control"></select>');
    //Display roles
    $.each(response.role, function(role) {
        $('#displayRole').append($('<option>', {

            value: response.role[role].role_id,
            text : response.role[role].role_name
        }));
    });

    //Display resources
    $('#formdiv').append(' Resource : <select id="displayResources" class="form-control"></select>');
    $.each(response.resource, function(res) {
        $('#displayResources').append($('<option>', {

            value: response.resource[res].resource_id,
            text : response.resource[res].resource_name
        }));
    });

    //Display permissions
    $('#formdiv').append(' Permissions : <div id="checkboxdiv" class="form-control"></div>');
    $.each(response.permission, function(per) {
        $('#checkboxdiv').append('&nbsp;&nbsp;&nbsp;');
        $('<input />', { type: 'checkbox', id:per, class:'checkbox', value: response.permission[per].permission_id }).appendTo('#checkboxdiv');
        $('<label />', { 'for': per, text: response.permission[per].permission_name }).appendTo('#checkboxdiv');
    });


}

$(document).ready(function () {

    //When add button is clicked show him the UI for setting the permissions
    $(document).on('click','#add',function () {
        $('.row').empty();
        display();
    });

    //Bind event for checkbox
    $(document).on('click','.checkbox',function () {
        sendPermissions(this,this.checked);
    });

    //Bind event when any select dropdown is change
    $(document).on('change','select', function() {
        selectCheckbox(checkboxResponseObj);
    });
});