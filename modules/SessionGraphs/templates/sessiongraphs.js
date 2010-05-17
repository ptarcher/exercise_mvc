$(function () {
    // Call the Open Streetmap API
    //OSM_Init();
    // Call the googlemaps API
    //GoogleMaps_Init();

    var options = {
        lines: { show: true },
        points: { show: true },
        xaxis: { tickDecimals: 0, tickSize: 1 }
    };
    var data = [];
    var placeholder = $("#placeholder");
    
    $.plot(placeholder, data, options);

    // fetch one series, adding to what we got
    var alreadyFetched = {};
    
    // then fetch the data with jQuery
    function onDataReceived(series) {
        // let's add it to our current data
        if (!alreadyFetched[series.label]) {
            alreadyFetched[series.label] = true;
            data.push(series);
        }

        // and plot all we got
        $.plot(placeholder, data, options);
    }
        
    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=speed",
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
    });

    // TODO: The session date needs to be dynamic */
    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=heartrate",
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
    });

    GoogleMaps_Init();
});

