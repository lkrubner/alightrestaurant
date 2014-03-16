
//	modal/dialog for Reservations & wine cellar pages
//	main code for dialog (depends on jQuery UI plugin)
function showTheModalForThisOccurrence(idOfElement) {
    $('.reservation_dialog').hide();
    var thisSelector = '#' + idOfElement; 
    $(thisSelector).show(); 
}






