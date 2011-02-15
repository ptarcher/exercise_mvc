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

function getAddSessionAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();

	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Sessions.createSession';
    parameters.session_date  = $(row).find('input#date').val();
    parameters.type_short    = $(row).find('select#type').val();
    parameters.description   = $(row).find('input#description').val();
    parameters.duration      = $(row).find('input#duration').val();
    parameters.distance      = $(row).find('input#distance').val();
    parameters.avg_speed     = $(row).find('input#avg_speed').val();
    parameters.avg_heartrate = $(row).find('input#avg_heartrate').val();
    parameters.comment       = $(row).find('textarea#comment').val();

	ajaxRequest.data = parameters;
 	
	return ajaxRequest;
}

function getUpdateSessionAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
	
    // TODO, Add all the session paramaters
	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Sessions.updateSession';
    parameters.session_date  = $(row).find('input#session_date').val();
    parameters.type_short    = $(row).find('select#type').val();
    parameters.description   = $(row).find('input#description').val();
    parameters.duration      = $(row).find('input#duration').val();
    parameters.distance      = $(row).find('input#distance').val();
    parameters.avg_speed     = $(row).find('input#avg_speed').val();
    parameters.avg_heartrate = $(row).find('input#avg_heartrate').val();
    parameters.comment       = $(row).find('textarea#comment').val();

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}

$(document).ready( function() {
	$('.addRowSession').click( function() {
		coreHelper.ajaxHideError();
		$(this).toggle();
        $('#norecords').toggle();
		
		var numberOfRows = $('table#editSessions')[0].rows.length;
		var newRowId = 'row' + numberOfRows;
	
		$(' <tr id="'+newRowId+'">'+
				'<td><input id="date"          value=""        size=25></td>'+
				'<td><select id="type"></select></td>'+
				'<td><input id="description"   value="Description" size=25></td>'+
				'<td><input id="duration"      value="Duration"    size=25></td>'+
				'<td><input id="distance"      value="Distance"    size=25></td>'+
				'<td><input id="avg_speed"     value="Avg Speed"   size=25></td>'+
				'<td><input id="avg_heartrate" value="Avg HR"      size=25></td>'+
                '<td><textarea cols=20 rows=3 id="comment">Comments</textarea>'+
				'<td><img src="themes/default/images/ok.png"     class="addsession" href="#"></td>'+
	  			'<td><img src="themes/default/images/remove.png" class="cancel"></td>'+
	 		'</tr>')
	  			.appendTo('#editSessions');

        /* Add callbacks */
		$('tr#'+newRowId).keypress( submitSessionOnEnter );
		$('.addsession').click( function(){ 
                $.ajax( getAddSessionAJAX($('tr#'+newRowId)) ); 
                } );
		$('.cancel').click(function() { 
                coreHelper.ajaxHideError(); 
                $(this).parents('tr').remove();  
                $('.addRowSession').toggle(); }
                );
        $("input#date").dateplustimepicker({
            timeFormat: 'hh:mm:ss',
            hourGrid: 3,
            showSeconds: true,
            showMinutes: true,
            step: {hours: 3},
        });

        /* Grab the select list */
        $.getJSON( '', { module: 'APIAccess',
                         format: 'json',
                         method: 'Sessions.getTrainingTypes',
                       },
                   function(data) {
                        $.each(data, function(val, item){
                            $('<option value="'+val+'">'+item+'</option>').appendTo("select#type");
                        });
                   });
	 } );
	
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

					if(idName == 'description' || idName == 'duration'  ||
                       idName == 'distance'    || idName == 'avg_speed' ||
                       idName == 'avg_heartrate')
					{
                        if (idName == 'type') {
                            width='3';
                        } else if (idName == 'description') {
                            width='10';
                        } else if (idName == 'duration') {
                            width='8';
                        } else if (idName == 'distance') {
                            width='5';
                        } else if (idName == 'avg_speed') {
                            width='5';
                        } else if (idName == 'avg_heartrate') {
                            width='5';
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
		$(this).find('.addsession').click();
	}
}
