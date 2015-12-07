/**
 * This document manages any javascript tasks for the addlink.php and editlink.php pages.
 */

/**
 * Functions that occur once the page has been fully loaded.
 */
$(document).ready(function () {
    // jQuery Selectors -- assigned to variables to prevent redundant selection
    var alertLocation = $("#alertLocation"),
        linkName = $("#linkName"),
        url = $("#URL"),
        classForAdd = $("#classForAdd");

    linkName.focus();

    /**
     * This function call enables selectize.js to turn the select element on the page into a hybrid text box and select
     * element. Note: this is only called when the element using the ID is a select. This is because, in some cases, the
     * element will be predetermined and not-selectable.
     */
    if(classForAdd.is("select")) {
        classForAdd.selectize({
            // Sorts the options in ascending order
            sortField: 'text'
        });
    }

    /**
     * This function call specifies what actions should be taken prior to the form executing the PHP submit script.
     */
    $('#linkAction').submit(function() {
        // Tracks if any errors were found in the subsequent checks
        var errorsFound = false;

        // Holds the error text that will be displayed to the user; initially holds just an informative lead.
        // Note: this won't be used unless errorsFound is set to true.
        var errorText = "<strong>Error!</strong>";

        // If an error was shown last time the user attempted to submit the form, remove it now
        alertLocation.empty();

        // Check if the user didn't enter a name for the link
        if(!linkName.val() || linkName.val() == "") {
            // Add the has-error class to the input's parent form-group div
            linkName.parent().parent().addClass('form-group has-error');

            // Toggle errorsFound to stop the form from submitting
            errorsFound = true;

            // Append error text appropriately
            errorText += " You didn't enter a valid link name!";
        } else {
            // Otherwise, remove any error class that was applied to the field
            linkName.parent().parent().removeClass('has-error');
        }

        // Check if the user didn't enter a URL for the link
        if(!url.val() || url.val() == "") {
            // Add the has-error class to the input's parent form-group div
            url.parent().parent().addClass('form-group has-error');

            // Toggle errorsFound to stop the form from submitting
            errorsFound = true;

            // Append error text appropriately
            errorText += " You didn't enter a valid URL!";
        } else {
            // Otherwise, remove any error class that was applied to the field
            url.parent().parent().removeClass('has-error');
        }

        // Check if the user didn't select a class for the link
        if(!classForAdd.val() || classForAdd.val() == "") {
            // Add the has-error class to the input's parent form-group div
            classForAdd.parent().parent().addClass('form-group has-error');

            // Toggle errorsFound to stop the form from submitting
            errorsFound = true;

            // Append error text appropriately
            errorText += " You didn't select a valid class!";
        } else {
            // Otherwise, remove any error class that was applied to the field
            classForAdd.parent().parent().removeClass('has-error');
        }

        if(errorsFound) {
            // If errors were found, append an alert div to the alertLocation div with the error text set previously.
            alertLocation.append($("<div>").attr("class", "alert alert-danger").html(errorText));

            // Terminate the submission to prevent the bad input from being submitted
            return false;
        }
    });
});