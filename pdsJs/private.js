$(function(){

    $('#res_bc_wcellar a').unbind('click');


    $('#cellar_pg a').unbind('click');


    $('.form-submit').click(function(e){
	e.preventDefault();
	$(this).closest("form").submit();
    });


    if($('body').is('.std') || $('body').is('.profile')){	
	$('.sel-custom').selectbox();
    }



    //	modal/dialog for Reservations & wine cellar pages
    //	main code for dialog (depends on jQuery UI plugin)
    //	close dialog when click outside it.....
    $( document ).on('click', '.ui-widget-overlay', function(){
	$('.reservation_dialog').dialog('close');
    }); 


    $('.reservation_dialog').dialog({
	modal:true,
	autoOpen: false,
	width: 548,
	draggable: false,
	dialogClass: 'dialogFixed',
	buttons: {
	    "CANCEL": function() {
		$(this).dialog("close");
	    }
	},
	//	open: onOpen
    });




    $('.private-reservations-dropdown').selectbox();


    
});


//	modal/dialog for Reservations & wine cellar pages
//	main code for dialog (depends on jQuery UI plugin)
function showTheModalForThisOccurrence(idOfElement) {
    var thisSelector = '#' + idOfElement; 
    $(thisSelector).dialog("open"); 

}



function showThisInput(idOfElement) {
    var selectString = '#' + idOfElement; 
    $(selectString).show(); 
}



function submitGuestList() {
    var isReadyToSubmit = true; 

    $('.mandatory').each(function() {
	if ($(this).val() == '') {
	    alert("Please fill in this information for " + $(this).attr('placeholder')); 
	    isReadyToSubmit = false; 
	    return false;
	}
    });

    if (isReadyToSubmit) {
	document.getElementById("private_reservations_guest_list_form").submit(); 
    } else {
	return false; 
    }
}





