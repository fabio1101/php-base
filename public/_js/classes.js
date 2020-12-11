/**
 * Javascript class to build redirector class
 */
class Redirector {

    /**
     * Constructor class that receives the main url to redirect
     */
    constructor(url) {

        // Build the new form element
        this.form = $(document.createElement('form'))
            .attr('action', url)
            .addClass('ui-hide')
            .attr('method', 'post');

        // Add the form to the body of the page
        $('body').append(this.form)
    }

    /**
     * Function to add a new parameter to the form (hidden input)
     */
    addParam(name, value) {

        // Create the input and append it to the form
        $(document.createElement('input'))
            .attr('type', 'hidden')
            .attr('name', name)
            .attr('value', value)
            .appendTo(this.form);
    }

    /**
     * Send the form request
     */
    submit() {

        $(this.form).submit();
    }
}
