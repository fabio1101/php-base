// Add ready listeners to generic actions
$(document).ready(function() {

    // Bind listener for redirect class
    $(document).on('click','.redirect', function(e) {
        // Prevent regular action from redirect click
        e.preventDefault();

        // If this button is not disabled then do redirection, else do nothing
        if (!$(this).hasClass("disabled")) {

            // Get the value from the attribute list
            var dir = $(this).data("dir");

            // Split the dir with underscore
            dir = dir.split('_');

            var url = dir.join('/');

            // If it has a var attribute then add it to the call, else do clean redirection
            if ($(this).hasClass("var")) {
                redirect('/' + url, 'id=' + $(this).data('id'));
            } else {
                redirect('/' + url, '');
            }
        }
    });

    // Convert all classes tltip into tooltips
    $(".tltip").tooltip();

    // Convert tables to datatables if at least one exists
    if ($(".datatable").length > 0) {
        $(".datatable").dataTable({
            responsive: true
        });
    }

    // Datepicker call on all date classes
    $(".date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat : 'yy-mm-dd',
        dayNamesMin : [ "D", "L", "M", "Mi", "J", "V",
        "S" ],
        monthNamesShort : [ "Ene", "Feb", "Mar", "Abr",
        "May", "Jun", "Jul", "Ago", "Sep",
        "Oct", "Nov", "Dic" ],
        beforeShow : function() {
            $(".ui-datepicker").css('font-size', 13);
        }
    });
});