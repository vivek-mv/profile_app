var totalPage = '';
var curPage = '1';
$(document).ready(function(){
    var limit = '0,5';
    var order = 'DESC';
    var upShape = 'glyphicon glyphicon-triangle-top';
    var downShape = 'glyphicon glyphicon-triangle-bottom';

    //Populate the table when the page is entered for the first time
    //Make search data as empty string , so that all the rows are displayed
    handleAjax.sendAjax("ajax.php", '','ASC','employee.firstName', '0,5' );

    //Register event for search input when it gets empty
    $('.getData').on( "keyup", function(){

        if ( $.trim($('.getData').val()) == '' ) {
            //Display all the rows
            handleAjax.sendAjax("ajax.php", '','ASC','employee.firstName','0,5' );
        }
    });

    //Register event for search submission
    $("form").submit(function() {
        var searchInput = $.trim($('.getData').val());
        handleAjax.sendAjax("ajax.php", searchInput,'ASC','employee.firstName','0,5' );
        return false;
    });

    //Register event for sorting
    $('.sort').click(function () {

        var sortBy = 'employee.firstName';

        var searchInput = $.trim($('.getData').val());
        var sortText = $.trim($(this)[0].innerText);

        if ( sortText == "Email" ) {
            sortBy = 'employee.email';
        }

        handleAjax.sendAjax("ajax.php", searchInput, order, sortBy,limit);

        //change the sort order and change the sort arrow shape
        if ( order === 'DESC' ) {
            order = 'ASC';
            $(this).children().removeClass(upShape).addClass(downShape);
            
        } else {
            order = 'DESC';
            $(this).children().removeClass(downShape).addClass(upShape);
        }
    });
    
    //Register event for paginationg
    $(document).on('click', '.page' , (function () {
        var currentPage = $(this)[0].innerText;
        var limit1 = (currentPage * 5) - 5;
        var limit2 = 5;
        limit = limit1 + ',' +  limit2;
        var searchInput = $.trim($('.getData').val());
        handleAjax.sendAjax("ajax.php", searchInput, 'ASC', 'employee.firstName',limit);
    }));

    //Register event for displaying pagination buttons
    $(document).on('click', '.displayPageButton' , (function () {
        var pageNo = $(this)[0].innerText;
        $('.appendBtn').empty();
        handlePageNumbers(pageNo);
    }));
});

var handleAjax = {
    sendAjax :
     /**
     * Sends an ajax request
     * @param String
     * @param String
     * @retrun void
     */
     function (searchUrl,searchData,sortOrder,sortByFieldName,limitBy) {
        $.ajax({
            url: searchUrl,
            data: {
                data: searchData,
                order: sortOrder,
                sortBy: sortByFieldName,
                limit: limitBy,
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
                        $('.appendBtn').empty();
                    } else {
                        alert('you need to login ');
                        location.reload();
                    }

                } else {
                    totalPage = response[0].totalPage;
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

         $('.appendBtn').empty();
         //Display the pagination buttons
         handlePageNumbers(curPage);
    }
}
function handlePageNumbers(currentPage) {
     curPage = currentPage;
     var startPage = (curPage < 5)? 1 : curPage - 4;
     var endPage = 8 + startPage;
     endPage = (totalPage < endPage) ? totalPage : endPage;
     var diff = startPage - endPage + 8;
     startPage -= (startPage - diff > 0) ? diff : 0;

     if (startPage > 1) {
         $('.appendBtn').append('<li class="page displayPageButton"><a href="#">' + 'First' + '</a></li>');
     }
     for(var i=startPage; i<=endPage; i++){
         $('.appendBtn').append('<li class="page displayPageButton"><a href="#">' + i + '</a></li>');
     }
     if (endPage < totalPage) {
         $('.appendBtn').append('<li class="page displayPageButton"><a href="#">' + 'Last' + '</a></li>');
     }
}




























