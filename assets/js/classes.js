$('#classSearch').tooltip({'trigger':'focus', 'title': 'Hit ENTER to search'});

$(".delete-form").submit(function() {
    return confirm('Are you sure you want to delete this item?');
});