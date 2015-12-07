/**
 * This file is used by classes.php and links.php, as they have similar structures and matching IDs and classes used
 * below.
 */


/**
 * Functions that occur once the page has been fully loaded.
 */
$(document).ready(function () {
    /**
     * Enables a tooltip that appears above the search box when clicked on that explains how to submit the search field
     */
    $('#search').tooltip({'trigger': 'focus', 'title': 'Hit ENTER to search'});

    /**
     * Requires the user to confirm deletion before submitting the item for deletion. If the user declines, the item is not
     * deleted.
     */
    $(".delete-form").submit(function () {
        return confirm('Are you sure you want to delete this item?');
    });
});