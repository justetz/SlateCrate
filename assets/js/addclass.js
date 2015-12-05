$(document).ready(function () {
    $('#inputCategory').selectize({
        sortField: 'text'
    });

    $('#addClass').submit(function() {
        var alertLocation = $("#alertLocation"),
            className = $("#className"),
            inputCategory = $("#inputCategory"),
            errorsFound = false,
            errorText = "<strong>Error!</strong>";


        alertLocation.empty();


        if(!className.val() || className.val() == "") {
            className.parent().parent().addClass('form-group has-error');
            errorsFound = true;
            errorText += " You didn't enter a valid class name!";
        } else {
            className.parent().parent().removeClass('has-error');
        }

        if(!inputCategory.val() || inputCategory.val() == "") {
            inputCategory.parent().parent().addClass('form-group has-error');
            errorsFound = true;
            errorText += " You didn't select a valid prefix!";
        } else {
            inputCategory.parent().parent().removeClass('has-error');
        }

        if(errorsFound) {
            alertLocation.append($("<div>").attr("class", "alert alert-danger").html(errorText));
            return false;
        }
    });
});