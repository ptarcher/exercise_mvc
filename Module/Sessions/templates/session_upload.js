$(document).ready( function() {
    $('a#planned_yes').click( function() {
        $.ajax({
            url: "index.php?" +
                    "module=APIAccess"+
                    "&method=Plans.updatePlanSession"+
                    "&plan_date="+encodeURIComponent(plan_date)+
                    "&session_date="+encodeURIComponent(session_timestamp),
            method: 'GET',
            dataType: 'json',
            success: function (result, textStatus, XMLHttpRequest) {
                /*if (result['result'] != "success") {
                }*/
            }
        });
    } );
});
