/**
 * This document manages any JavaScript tasks for the addclass.php and editclass.php pages.
 */

/**
 * Gets the full list of prefixes, as defined in resources/prefixes.json
 * @type {Array}
 */
var prefixes = [];
$.ajax({
    url : "resources/prefixes.json",
    dataType: "json",
    success : function (data) {
        prefixes = data;
        console.log(prefixes);
    }

});

/**
 * Functions that occur once the page has been fully loaded.
 */
$(document).ready(function () {
    // jQuery Selectors -- assigned to variables to prevent redundant selection
    var alertLocation = $("#alertLocation"),
        className = $("#className"),
        inputCategory = $("#inputCategory");

    className.focus();

    /**
     * This function call enables selectize.js to turn the select element on the page into a hybrid text box and select
     * element.
     */
    inputCategory.selectize({
        // Sorts the options in ascending order
        sortField: 'text'
    });

    /**
     * This function call specifies what actions should be taken prior to the form executing the PHP submit script.
     */
    $('#classAction').submit(function() {
        // Tracks if any errors were found in the subsequent checks
        var errorsFound = false;

        // Holds the error text that will be displayed to the user; initially holds just an informative lead.
        // Note: this won't be used unless errorsFound is set to true.
        var errorText = "<strong>Error!</strong>";

        // If an error was shown last time the user attempted to submit the form, remove it now
        alertLocation.empty();

        // Check if the user didn't enter a name for the class
        if(!className.val() || className.val() == "") {
            // Add the has-error class to the input's parent form-group div
            className.parent().parent().addClass('form-group has-error');

            // Toggle errorsFound to stop the form from submitting
            errorsFound = true;

            // Append error text appropriately
            errorText += " You didn't enter a valid class name!";
        } else {
            // Otherwise, remove any error class that was applied to the field
            className.parent().parent().removeClass('has-error');
        }

        // Check if the user didn't select a prefix for the class
        if((!inputCategory.val() || inputCategory.val() == "") || prefixes.indexOf(inputCategory.val()) == -1) {
            // Add the has-error class to the input's parent form-group div
            inputCategory.parent().parent().addClass('form-group has-error');

            // Toggle errorsFound to stop the form from submitting
            errorsFound = true;

            // Append error text appropriately
            errorText += " You didn't select a valid prefix!";
        } else {
            // Otherwise, remove any error class that was applied to the field
            inputCategory.parent().parent().removeClass('has-error');
        }

        if(errorsFound) {
            // If errors were found, append an alert div to the alertLocation div with the error text set previously.
            alertLocation.append($("<div>").attr("class", "alert alert-danger").html(errorText));

            // Terminate the submission to prevent the bad input from being submitted
            return false;
        }
    });
});