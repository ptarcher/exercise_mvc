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

$(document).ready( function() {
    $('table#plans').tablesorter({
            widgets: ['zebra',],
    });

    $('.addPlan').click( function() {
        $(this).toggle();

        var numberOfRows = $('table#plans')[0].rows.length;
        var newRowId = 'row' + numberOfRows;

        $(' <tr id="'+newRowId+'">\
            <td><input id="plan_date"          value="Week Date"   size=25></td>\
            <td><input id="plan_period"        value="Period"      size=25></td>\
            <td><input id="plan_description"   value="Description" size=25></td>\
            <td><textarea cols=20 rows=3 id="plan_comment">Comments</textarea>\
            <td><img src="themes/default/images/ok.png"            class="addplan" href="#"></td>\
            <td><img src="themes/default/images/remove.png"        class="cancel"></td>\
            </tr>')
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
