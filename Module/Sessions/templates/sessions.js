function getDeleteSessionAJAX( session_date )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
		
	var parameters = {};
	parameters.module = 'APIAccess';
	parameters.format = 'json';
 	parameters.method =  'Sessions.deleteSession';
    parameters.session_date  = session_date;
	
	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}

function getUpdateSessionAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
	
    // Add all the session paramaters
	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Sessions.updateSession';
    parameters.session_date  = $(row).find('input#session_date').val();
    parameters.type_short    = $(row).find('select#type').val();
    parameters.description   = $(row).find('input#description').val();
    parameters.comment       = $(row).find('textarea#comment').val();

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}

$(document).ready( function() {
	// when click on deleteuser, the we ask for confirmation and then delete the user
	$('.deleteSession').click( function() {
			coreHelper.ajaxHideError();
			var idRow = $(this).attr('id');
            var row   = $(this).parent().parent().parent();
			var nameToDelete = row.find('input#description').val() || row.find('td#description').html();
			var session_date = row.find('input#session_date').val();
			if(confirm('Are you sure you want to delete "'+nameToDelete+'" (date = '+session_date+')')) {
				$.ajax( getDeleteSessionAJAX( session_date ) );
			}
		}
	);
	
	var alreadyEdited = new Array;
	$('.editSession')
		.click( function() {
			coreHelper.ajaxHideError();
			var idRow = $(this).attr('id');

            /* Check if we are already editing */
			if(alreadyEdited[idRow]==1) return;
			alreadyEdited[idRow] = 1;

			$('tr#'+idRow+' .editable').each(
				// make the fields editable
				// change the EDIT button to VALID button
				function (i,n) {
					var contentBefore = $(n).html();
					var idName = $(n).attr('id');

					if(idName == 'description')
					{
                        if (idName == 'type') {
                            width='3';
                        } else if (idName == 'description') {
                            width='10';
                        } else {
                            width='20';
                        }
						var contentAfter = '<input id="'+idName+'" value="'+contentBefore+'" size="'+width+'">';
						$(n)
							.html(contentAfter)
							.keypress( submitSessionOnEnter );
                    } else if (idName == 'type') {
                        var contentAfter = '<select id="'+idName+'"></select>';
                        $(n).html(contentAfter);
                    } else if (idName == 'comment') {
						var contentAfter = '<textarea cols=20 rows=3 id="'+idName+'">'+contentBefore.replace(/<br *\/? *>/gi,"\n")+'</textarea>';
						$(n).html(contentAfter);
					}
                }
            );

            /* Grab the training list */
            $.getJSON( '', { module: 'APIAccess',
                    format: 'json',
                    method: 'Sessions.getTrainingTypes',
                    },
                    function(data) {
                        $.each(data, function(val, item){
                            $('tr#'+idRow).find('select#type').append('<option value="'+val+'">'+item+'</option>');
                        });
                    });


			$(this)
				.toggle()
				.parent()
				.prepend( $('<img src="themes/default/images/ok.png" class="updateSession">')
							.click( function(){ $.ajax( getUpdateSessionAJAX( $('tr#'+idRow) ) ); } ) 
					);

            /* TODO: Get the pager to re-draw */
            $('table#editSessions').trigger("update");
		}
	);
	
	$('td.editable').click( function(){ $(this).parent().find('.editSession').click(); } );

    $('table#editSessions').tablesorter({
            widgets: ['zebra',],
    }).tablesorterPager({
            container: $('#pager'),
            size: 20,
    });
});
 
function submitSessionOnEnter(e)
{
	var key=e.keyCode || e.which;
	if (key==13)
	{
		$(this).parent().find('.updateSession').click();
	}
}
