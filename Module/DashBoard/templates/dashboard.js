$(document).ready( function() {
    //var monday = Date.parse('monday').toString('yyyy-MM-dd');
    var monday = Date.parse('2010-06-14').toString('yyyy-MM-dd');

    $.ajax({
        url: "index.php?"+
                        "module=APIAccess"+
                        "&method=Plans.getDailyPlans"+
                        "&week_date="+encodeURIComponent(monday),
        method: 'GET',
        dataType: 'json',
        success: function (weeks, textStatus, XMLHttpRequest) {
            $('div#week_of_year').html(weeks);
        }
    });


    /* Start of week is monday, end of week is sunday */
    var sunday = Date.parse('next sunday').toString('yyyy-M-d');

    $('div#monday').html(monday);
    $('div#sunday').html(sunday);
});
