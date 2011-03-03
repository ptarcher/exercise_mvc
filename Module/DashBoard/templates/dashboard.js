$(document).ready( function() {
    today = new Date();

    var week   = today.getWeekOfYear();
    /* Start of week is monday, end of week is sunday */
    var monday = Date.parse('monday').toISOString();
    var sunday = Date.parse('next sunday').toString('yyyy-M-d');

    $('div#week_of_year').html('week of year = ' + week);
    $('div#monday').html(monday);
    $('div#sunday').html(sunday);
});
