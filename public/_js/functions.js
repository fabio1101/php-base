/**
 * Function to redirect to a specific page wiht or without values. the format is:
 * ('/index/index', 'dat1=xxx&dat2=yyy&dat3=zzz')
 */
function redirect(action, params) {
    // Check if this requires params to be sent
    if (params) {

        // Build a new Form object with the action as the url sent
        var form = $(document.createElement('form')).attr('action', action);

        // Set the method as POST
        form.attr('method', 'POST');

        // Append the form to the body (empty)
        $('body').append(form);

        // Split params per & symbol
        params = params.split('&');

        // Loop the params
        for ( var i in params) {

            // Split name / value by symbol =
            var tmp = params[i].split('=');
            var key = tmp[0], value = tmp[1];

            // If the length of the array split was more than 2 was because it found more than one equal
            if (tmp.length > 2) {

                // Loop the vars and add those pieces that were split by error and join them on the value
                // var also joining them back with a = symbol (This happens on encrypted values that has = symbols)
                for (var i = 2; i <= (tmp.length - 1); i++) {
                    value += ('=' + tmp[i]);
                }
            }

            // Create the input and append it to the form
            $(document.createElement('input')).attr('type', 'hidden').attr('name', key).attr('value', value).appendTo(form);
        }

        // Submit the form
        $(form).submit();

    // If no params need to be sent then do a simple redirection
    } else {
        window.location.href = action;
    }
    return false;
};

/**
 * Mask by opening the block modal
 */
function mask() {
    $("#block").modal('show');
};

/**
 * Unmask by closing the block modal
 */
function unmask() {
    $("#block").modal('hide');
};

/**
 * Format a number to money format with symbol $ and the value separated over thousands
 */
function moneyFormat(money) {
    // parse this number to float
    var moneyNumber = parseFloat(money);

    // If it could not be converted to float then return empty
    if (isNaN(moneyNumber)) {
        return '';
    }

    // Do the conversion with 2 numbers and thousands separator
    return '$ ' + moneyNumber
        .toFixed(2)
        .replace(/\d(?=(\d{3})+\.)/g, '$&,');
}