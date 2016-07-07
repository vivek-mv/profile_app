$(document).ready(function(){

    var order = 'DESC';
    var upShape = 'glyphicon glyphicon-triangle-top';
    var downShape = 'glyphicon glyphicon-triangle-bottom';
    //Populate the table when the page is entered for the first time
    //Make search data as empty string , so that all the rows are displayed
    handleAjax.sendAjax("ajax.php", '','ASC' );

    //Register event for search input when it empty
    $('.getData').on( "keyup", function(){

        if ( $.trim($('.getData').val()) == '' ) {
            //Display all the rows
            handleAjax.sendAjax("ajax.php", '','ASC' );
        }
    });

    //Register event for search submission
    $("form").submit(function() {
        var searchInput = $.trim($('.getData').val());
        handleAjax.sendAjax("ajax.php", searchInput,'ASC' );
        return false;
    });

    //Register event for sorting
    $('#name').click(function () {
        var searchInput = $.trim($('.getData').val());
        handleAjax.sendAjax("ajax.php", searchInput,order );
        $('#sortShape').removeClass(upShape).addClass(downShape);
        //change the sort order
        if ( order === 'DESC' ) {
            order = 'ASC';
            
        } else {
            order = 'DESC';
        }
    });
});

var handleAjax = {
    sendAjax :
     /**
     * Sends an ajax request
     * @param String
     * @param String
     * @retrun void
     */
     function (searchUrl,searchData,sortOrder) {
        $.ajax({
            url: searchUrl,
            data: {
                data: searchData,
                order:sortOrder,
                ajax: 1
            },
            dataType : 'json',
            type: "POST",
            success: function (response) {

                if ( response.error === '1') {
                    if( response.error_code === '404' ) {
                        //when no rows match the search
                        $("#tablebody").empty();
                        $("table").hide();
                        $('#noRecords').show();
                    } else {
                        alert('you need to login ');
                        location.reload();
                    }

                } else {

                    //populate the table using the response data
                    handleAjax.displayEmployeeDetails(response);
                }
            }
        });
    } ,
    displayEmployeeDetails :
     /**
     * Populate the table
     * @param String
     * @param String
     * @retrun void
     */
     function (jsonObj) {
        var tbody = $('#tablebody');
        tbody.empty();
        $("table").show();
        $('#noRecords').hide();

        $.each( jsonObj, function( row ) {
            var record = jsonObj[row];
            tbody.append(
                '<tr>' +
                '<td>' + record.firstName + ' ' + record.middleName + ' ' + record.lastName + '</td>' +
                '<td>' + record.gender + '</td>' +
                '<td>' + record.dob + '</td>' +
                '<td>' + record.phone + '</td>' +
                '<td>' + record.email + '</td>' +
                '<td>' + record.maritalStatus + '</td>' +
                '<td>' + record.employment + '</td>' +
                '<td>' + record.medium + '</td>' +
                '<td>' + record.residenceAddress + '</td>' +
                '<td>' + record.officeAddress + '</td>' +
                '<td>' + record.photo + '</td>' +
                '<td>' + record.edit + '</td>' +
                '<td>' + record.delete + '</td>' +
                '</tr>'
            );
        });
    }
}





























