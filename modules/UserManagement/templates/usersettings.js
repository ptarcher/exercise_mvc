function getUpdateSettingAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
	
    // TODO, Add all the session paramaters
	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'UserManagement.updateSettings';
    /*
    parameters.session_date  = $(row).find('input#session_date').val();
    parameters.type_short    = $(row).find('input#type').val();
    parameters.description   = $(row).find('input#description').val();
    parameters.duration      = $(row).find('input#duration').val();
    parameters.distance      = $(row).find('input#distance').val();
    parameters.avg_speed     = $(row).find('input#avg_speed').val();
    parameters.avg_heartrate = $(row).find('input#avg_heartrate').val();
    parameters.comment       = $(row).find('textarea#comment').val();*/

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}


$(document).ready( function() {
    $('table#settings').tablesorter({
            widgets: ['zebra',],
    });
    
	var alreadyEdited = new Array;
	$('.settings').click( function() {
			coreHelper.ajaxHideError();
			var idRow = $(this).attr('id');
			if(alreadyEdited[idRow]==1) return;
			alreadyEdited[idRow] = 1;
            /*
			$('tr#'+idRow+' .editable').each(
				// make the fields editable
				// change the EDIT button to VALID button
				function (i,n) {
					var contentBefore = $(n).html();
					var id = $(n).attr('id');
                    var contentAfter = '<input id="'+id+'" value="'+contentBefore+'">';
                    $(n).html(contentAfter)
                        .keypress(submitSessionOnEnter);
				}
			);
            */
			$(this).toggle().parent()
				.prepend( $('<img src="themes/default/images/ok.png" class="update">')
							.click( function(){ $.ajax( getUpdateSettingAJAX( $('tr#'+idRow) ) ); } ) 
					);
		}
	);
	
	$('td.editable').click( function(){ $(this).parent().find('.settings').click(); } );
});

function submitSessionOnEnter(e)
{
	var key=e.keyCode || e.which;
	if (key==13)
	{
		//$(this).parent().find('.updateSession').click();
		//$(this).find('.addsession').click();
	}
}
