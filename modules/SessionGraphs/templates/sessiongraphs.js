$(function () {
    // Call the Open Streetmap API
    //OSM_Init();
    // Call the googlemaps API
    GoogleMaps_Init();

    // jqplot
    var jqplot_options = {
        title:session_date,
        seriesDefaults:{showMarker: false},
        /*
        series:[
            {label:'Heart Rate', }, 
            {label:'Speed', yaxis:'y2axis'}, 
        ],*/
        axesDefaults:{
            //tickOptions:{formatString:"%d"}, 
            autoscale:true, 
            useSeriesColor:true,
        },
        axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer,
                tickOptions:{formatString:'%H:%M:%S'},
                label:'Time',
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
            },
            yaxis:{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
            },
                  /*
            y2axis:{
                label:'Speed',
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
            },*/
        },
        highlighter: {
            sizeAdjust: 10,
            tooltipLocation: 'n',
            tooltipAxes: 'y',
            tooltipFormatString: '%.2f',
            useAxesFormatters: false
        },
        cursor: {
            show:true, 
            zoom:true
        },

        //legend: {location:'ne'},
    };

    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=heartrate",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Heart Rate';
                     options.axes.yaxis.label = 'Heart Rate (bpm)';
                     $.jqplot('heartrate', [series], options);
                 }
    });

    
    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=speed",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                    var options = jqplot_options;
                    options.title = 'Speed';
                    options.axes.yaxis.label = 'Speed (km/h)';
                    $.jqplot('speed', [series], options);
                 }
    });


    /*
    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=distance",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Distance';
                     options.axes.yaxis.label = 'Distance (km)';
                     $.jqplot('distance', [series], options);
                 }

    });*/

    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=altitude",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Altitude';
                     options.axes.yaxis.label = 'Elevation (m)';
                     $.jqplot('altitude', [series], options);
                 }

    });

    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=cadence",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Cadence';
                     options.axes.yaxis.label = 'Cadence (rpm)';
                     $.jqplot('cadence', [series], options);
                 }
    });

    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=power",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Power';
                     options.axes.yaxis.label = 'Power (watts)';
                     $.jqplot('power', [series], options);
                 }
    });

    $.ajax({
        url: "index.php?module=APIAccess&method=SessionGraphs.getSessionDataField&session_date="+encodeURIComponent(session_date)+"&field=temperature",
        method: 'GET',
        dataType: 'json',
        success: function (series) {
                     var options = jqplot_options;
                     options.title = 'Temperature';
                     options.axes.yaxis.label = 'Temperature (C)';
                     $.jqplot('temperature', [series], options);
                 }
    });
});

