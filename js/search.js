var totalPage = '';
var curPage = '1';
var totalRecords = '';
var limit = '0,5';
var limit1 = 0;
var limit2 = 4;
var lastBtn = 0;

$(document).ready(function(){
    var sortBy = 'employee.firstName';
    var order = 'DESC';
    var upShape = 'glyphicon glyphicon-triangle-top';
    var downShape = 'glyphicon glyphicon-triangle-bottom';

    //Populate the table when the page is entered for the first time
    //Make search data as empty string , so that all the rows are displayed
    handleAjax.sendAjax("ajax.php", '','ASC','employee.firstName', '0,5' );

    //Register event for search input when it gets empty
    $('.getData').on( "keyup", function(){
        curPage = '1';
        if ( $.trim($('.getData').val()) == '' ) {
            limit1 = (curPage * 5) - 5;
            limit2 = limit1+4;
            //Display all the rows
            handleAjax.sendAjax("ajax.php", '','ASC','employee.firstName','0,5' );
            order = 'DESC';
            $('.sort').children().removeClass(downShape).addClass(upShape);
        }
    });

    //Register event for search submission
    $("form").submit(function() {
        curPage = '1';
        var searchInput = $.trim($('.getData').val());
        limit1 = (curPage * 5) - 5;
        limit2 = limit1+4;
        handleAjax.sendAjax("ajax.php", searchInput,'ASC','employee.firstName','0,5' );
        order = 'DESC';
        $('.sort').children().removeClass(downShape).addClass(upShape);
        return false;
    });

    //Register event for sorting
    $('.sort').click(function () {

        curPage = '1';
        sortBy = 'employee.firstName';
        var searchInput = $.trim($('.getData').val());
        var sortText = $.trim($(this)[0].innerText);

        if ( sortText == "Email" ) {
            sortBy = 'employee.email';
        }

        limit1 = (curPage * 5) - 5;
        limit2 = limit1+4;

        handleAjax.sendAjax("ajax.php", searchInput,order,sortBy,'0,5' );

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
        var sortOrder = order;
        if ( order == 'DESC' ) {
            sortOrder = 'ASC';
        } else {
            sortOrder = 'DESC'
        }
        var currentPage = $(this)[0].innerText;
        if ( currentPage == totalPage ) {
            lastBtn = 1;
        }
        limit1 = (currentPage * 5) - 5;
        limit2 = 5;
        limit = limit1 + ',' +  limit2;
        var searchInput = $.trim($('.getData').val());
        handleAjax.sendAjax("ajax.php", searchInput, sortOrder, sortBy,limit);
        limit2 = limit1+4;
        $('#showRowMsg').text('Showing '+limit1+'-'+limit2+' of ' + ' ' + totalRecords + ' entries');
    }));

    //Register event for first page button
    $(document).on('click', '#first', (function () {
        curPage = '1';
        var sortOrder = order;
        if ( order == 'DESC' ) {
            sortOrder = 'ASC';
        } else {
            sortOrder = 'DESC'
        }
        var searchInput = $.trim($('.getData').val());
        limit1 = (curPage * 5) - 5;
        limit2 = limit1+4;
        handleAjax.sendAjax("ajax.php", searchInput, sortOrder, sortBy,'0,5');
        $('#showRowMsg').text('Showing '+0+'-'+ 4 + ' of ' + ' ' + totalRecords + ' entries');
    }));

    //Register event for last page button
    $(document).on('click', '#last', (function () {
        curPage = totalPage;
        var sortOrder = order;
        if ( order == 'DESC' ) {
            sortOrder = 'ASC';
        } else {
            sortOrder = 'DESC'
        }
        var searchInput = $.trim($('.getData').val());
        limit = (totalPage * 5) - 5;
        limit1 = (curPage * 5) - 5;
        limit2 = limit1+4;
        lastBtn = 1 ;
        handleAjax.sendAjax("ajax.php", searchInput, sortOrder, sortBy,limit+',5');

    }));

    //Register event for displaying pagination buttons
    $(document).on('click', '.displayPageButton' , (function () {
        var pageNo = $(this)[0].innerText;
        $('.appendBtn').empty();
        handlePageNumbers(pageNo);
    }));

    //Register event to display the stackoverflow info
    $(document).on('click', '.showStackInfo', function () {
        displayStackInfo(this.children[0].value);
    });

});

var handleAjax = {
    sendAjax :
     /**
     * Sends an ajax request
     * @param String
     * @param String
     * @param String
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
                        $('#showRowMsg').text('');
                    } else {
                        alert('You are logged out. Please login again ');
                        location.reload();
                    }

                } else {
                    
                    totalPage = response[0].totalPage;
                    totalRecords = response[0].totalRecords;
                    //populate the table using the response data
                    handleAjax.displayEmployeeDetails(response);
                    if ( lastBtn === 1 ) {
                        $('#showRowMsg').text('Showing ' + (limit1+1) + '-'+ totalRecords +' of ' + ' ' + totalRecords + ' entries');
                        lastBtn = 0;
                    } else {
                        $('#showRowMsg').text('Showing '+(limit1+1)+'-'+ (limit2+1) +' of ' + ' ' + totalRecords + ' entries');
                    }

                }
            }
        });
    } ,
    displayEmployeeDetails :
     /**
     * Populate the table
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
                '<td class="showStackInfo" data-toggle="modal" data-target="#myModal">' + record.firstName + ' ' + record.middleName + ' ' + record.lastName +
                 '<input type="hidden" value=' + record.stackId + '>' + '</td>' +
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
         $('.appendBtn').append('<li id="first"><a href="#">' + 'First' + '</a></li>');
     }
     for(var i=startPage; i<=endPage; i++){
         var showActive = '';
         if ( i == curPage ) {
             showActive = 'active';
         }
         $('.appendBtn').append('<li class="page displayPageButton ' + showActive + '"><a href="#">' + i + '</a></li>');
     }
     if (endPage < totalPage) {
         $('.appendBtn').append('<li id="last" ><a href="#">' + 'Last' + '</a></li>');
     }
}   

function displayStackInfo(stackUserId) {
    if ( stackUserId == '0' ) {
        $('#noAccount').css('display','inline');
        $('.modal-body').css('display','none');
    } else {
        $('#noAccount').css('display','none');
        $('#invalidAccount').css('display','none');
        $('.modal-body').css('display','inline');
        $('#loaderImg').css('display','inline');
        $('.panel').hide();
        $.ajax({
            url: 'https://api.stackexchange.com/users/' + stackUserId + '?site=stackoverflow',
            dataType : 'json',
            success: function (response) {

                if ( response.items == '' || response.error_id   ) {

                    $('#loaderImg').css('display','none');
                    $('#invalidAccount').css('display','inline');
                } else {
                    $('#display-name').html(response.items[0].display_name);
                    $('#profile_pic').attr('src',response.items[0].profile_image);
                    $('#age').html(response.items[0].age);
                    $('#reputation').html(response.items[0].reputation);
                    $('#b_badges').html(response.items[0].badge_counts.bronze);
                    $('#s_badges').html(response.items[0].badge_counts.silver);
                    $('#g_badges').html(response.items[0].badge_counts.gold);
                    $('#location').html(response.items[0].location);
                    $('#link').attr('href',response.items[0].link);
                    $('#loaderImg').css('display','none');
                    $('.panel').show();
                }


            }
        });
    }

}

























