$(document).ready(function(){

    //Register event for form submission
    $("form").submit(function() {

        $.ajax({
            url: "http://localhost/profile_app/ajax.php",
            data: {
                data: $('.getData').val()
            },
            type: "POST",
            success : function (data) {
                //populate the table using the response data
                console.log(data);
                
            }
        });

        return false;
    });
});