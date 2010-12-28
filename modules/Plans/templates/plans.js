function getAddPlanAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();

	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Plans.createWeeklyPlan';
    parameters.week_date   = $(row).find('input#plan_date').val();
    parameters.period      = $(row).find('input#plan_period').val();
    parameters.description = $(row).find('input#plan_description').val();
    parameters.comment     = $(row).find('textarea#plan_comment').val();

	ajaxRequest.data = parameters;
 	
	return ajaxRequest;
}

function getDeletePlanAJAX( week_date )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();

	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Plans.deleteWeeklyPlan';
    parameters.week_date   = week_date;

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}



function getUpdatePlanAJAX( row )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
	
	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'Plans.updateWeeklyPlan';
    parameters.week_date   = $(row).find('input#plan_date').val();
    parameters.period      = $(row).find('input#plan_period').val();
    parameters.description = $(row).find('input#plan_description').val();
    parameters.comment     = $(row).find('textarea#plan_comment').val();

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}

$(document).ready( function() {
    $('table#plans').tablesorter({
            widgets: ['zebra',],
    });

    $('.addPlan').click( function() {
        $(this).toggle();

        var numberOfRows = $('table#plans')[0].rows.length;
        var newRowId = 'row' + numberOfRows;

        $(' <tr id="'+newRowId+'">'+
            '<td><input id="plan_date"          value="Week Date"   size=25></td>'+
            '<td><input id="plan_period"        value="Period"      size=25></td>'+
            '<td><input id="plan_description"   value="Description" size=25></td>'+
            '<td><textarea cols=20 rows=3 id="plan_comment">Comments</textarea>'+
            '<td><img src="themes/default/images/ok.png"     class="addplan" href="#"></td>'+
            '<td><img src="themes/default/images/remove.png" class="cancel"></td>'+
            '</tr>')
        .appendTo('#plans');

        /* Add callbacks */
        $('#'+newRowId).keypress( submitSessionOnEnter );
        $('.addplan').click( function() { 
            $.ajax( getAddPlanAJAX($('tr#'+  newRowId)) ); }
        );
        $('.cancel').click(function() { 
            coreHelper.ajaxHideError(); 
            $(this).parents('tr').remove();
            $('.addPlan').toggle(); }
            );
    } );

	$('.deletePlan').click( function() {
        coreHelper.ajaxHideError();
        var idRow = $(this).attr('id');
        var p     = $(this).parent();
        var pp    = p.parent();
        var ppp   = pp.parent();
        var row   = $(this).parent().parent().parent();
        var nameToDelete = row.find('input#description').val() || row.find('td#description').html();
        var week_date = row.find('input#week_date').val();
        if(confirm('Are you sure you want to delete "'+nameToDelete+'" (date = '+week_date+')')) {
            $.ajax( getDeletePlanAJAX( week_date ) );
        }
    });

	var alreadyEdited = new Array;
	$('.editPlan')
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
                    // TODO: Add a dropdown list for type
                    // Add a calender link for date
					if(idName == 'date'      || 
                       idName == 'period'      || 
                       idName == 'description')
					{
                        if (idName == 'avg_heartrate') {
                            width='5';
                        } else {
                            width='20';
                        }
						var contentAfter = '<input id="'+idName+'" value="'+contentBefore+'" size="'+width+'">';
						$(n)
							.html(contentAfter)
							.keypress( submitSessionOnEnter );
					}
					if (idName == 'comment')
					{
						var contentAfter = '<textarea cols=20 rows=3 id="'+idName+'">'+contentBefore.replace(/<br *\/? *>/gi,"\n")+'</textarea>';
						$(n).html(contentAfter);
					}
				}
			);
			$(this)
				.toggle()
				.parent()
				.prepend( $('<img src="themes/default/images/ok.png" class="updateplan">')
							.click( function(){ $.ajax( getUpdatePlanAJAX( $('tr#'+idRow) ) ); } ) 
					);
		}
	);
	
	$('td.editable').click( function(){ $(this).parent().find('.editPlan').click(); } );


});

function submitSessionOnEnter(e)
{
	var key=e.keyCode || e.which;
	if (key==13)
	{
		$(this).parent().find('.updateplan').click();
		$(this).find('.addplan').click();
	}
}
