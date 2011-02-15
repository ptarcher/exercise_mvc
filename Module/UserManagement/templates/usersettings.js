function getUpdateUserSettingAJAX( id, value )
{
	var ajaxRequest = coreHelper.getStandardAjaxConf();
	coreHelper.toggleAjaxLoading();
	
    // TODO, Add all the session paramaters
	var parameters    = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method = 'UserManagement.updateSetting';

    parameters.id     = id;
    parameters.value  = value;

	ajaxRequest.data = parameters;
	
	return ajaxRequest;
}


$(document).ready( function() {
    $('table#settings').tablesorter({
            widgets: ['zebra',],
    });
    
	var alreadyEdited = new Array;
	$('.settings').click( function() {
			$(this).toggle().parent()
				.prepend( $('<img src="themes/default/images/ok.png" class="update">')
							.click( function(){ $.ajax( getUpdateSettingAJAX( $('tr#'+idRow) ) ); } ) 
					);
		}
	);
	
	$('td.editable').click( function() { 
			coreHelper.ajaxHideError();
			var idRow = $(this).attr('id');
			if(alreadyEdited[idRow]==1) return;
			alreadyEdited[idRow] = 1;

            var id             = 'edit_'+$(this).attr('id');
            var content_before = $(this).html();
            if ($(this).attr('id') == 'password') {
                var content_after  = '<input id="'+id+'" type=password value="">';
            } else {
                var content_after  = '<input id="'+id+'" type=text value="'+content_before+'">';
            }
            $(this).html(content_after)
                   .keypress(submitSessionOnEnter);

            $("input#edit_dob").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd/mm/yy',
            });
    } );
});

function submitSessionOnEnter(e)
{
	var key=e.keyCode || e.which;
	if (key==13)
	{
        var id    = $(this).attr('id');
        var value = $(this).find('input#'+'edit_'+id).val();

        $.ajax(getUpdateUserSettingAJAX(id, value));
		//$(this).parent().find('.updateSession').click();
		//$(this).find('.addsession').click();
	}
}
