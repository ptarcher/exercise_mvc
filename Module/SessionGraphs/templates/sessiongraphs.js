$(function () {
    if (MapData == 'openstreetmap') {
        // Call the Open Streetmap API
        OSM_Init();
    } else if (MapData == 'google') {
        // Call the googlemaps API
        GoogleMaps_Init("index.php?module=APIAccess&"+
            "method=SessionGraphs.getGPXData&"+
            "format=gpx&"+
            "session_date="+encodeURIComponent(session_date));
    }

    // jqplot
    var jqplot_options = {
        title:session_date,
        seriesDefaults:{
            showMarker: false, 
            shadow:false,
            thresholdLines: {
                yValues: [0.0],
                showLabel: true,
            },
        },
        axesDefaults:{
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

    var graphs = [
        {
            id    : 'graph_speed',
            title : 'Speed',
            field : 'speed',
            seriesDefaults: {showMarker: false, shadow:false,
                             thresholdLines: {yValues: [$('input#avg_speed').val()], showLabel: true,}},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Speed (km/h)',
                numberTicks  : 8,
            }
        },

        {
            id    : 'graph_heartrate',
            title : 'Heart Rate',
            field : 'heartrate',
            seriesDefaults: {showMarker: false, shadow:false,
                             thresholdLines: {yValues: [$('input#avg_heartrate').val()], showLabel: true,}},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Heart Rate (bpm)',
                numberTicks  : 8,
            }
        },

        /*
        {
            id    : 'graph_distance',
            title : 'Distance',
            field : 'distance',
            seriesDefaults: {showMarker: false, shadow:false},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Distance (km)',
            }
        },*/

        {
            id    : 'graph_altitude',
            title : 'Altitude',
            field : 'altitude',
            seriesDefaults: {showMarker: false, shadow:false, 
                             //fill:true, fillToZero: false,
            },
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Elevation (m)',
                numberTicks  : 8,
                autoscale    : true,
            }
        },

        {
            id    : 'graph_gradient',
            title : 'Gradient',
            field : 'gradient',
            seriesDefaults: {showMarker: false, shadow:false,
                             thresholdLines: {yValues: [0.0], showLabel: true,}},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Gradient (%)',
                min          : -20,
                max          : 20,
                tickInterval : 5,
            }
        },

        {
            id    : 'graph_cadence',
            title : 'Cadence',
            field : 'cadence',
            seriesDefaults: {showMarker: false, shadow:false},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Cadence (rpm)',
            }
        },

        {
            id    : 'graph_power',
            title : 'Power',
            field : 'power',
            seriesDefaults: {showMarker: false, shadow:false,
                             thresholdLines: {yValues: [0.0], showLabel: true,}},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Power (watts)',
                min          : -100,
                //max        : 1200,
                tickInterval : 100,
            }
        },

        {
            id    : 'graph_temperature',
            title : 'Temperature',
            field : 'temperature',
            seriesDefaults: {showMarker: false, shadow:false},
            yaxis :{
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                label        : 'Temperature (C)',
            }
        },
    ];

    $(".expand h3").addClass("active");
    $(".expand h3").click(function(){
        $(this).next().toggle();
        $(this).toggleClass("active");
    });

    $('table#climbs').tablesorter({
            widgets: ['zebra',],
    });

    $('table#laps').tablesorter({
            widgets: ['zebra',],
    });

    $('table#zones').tablesorter({
            widgets: ['zebra',],
    });

    function getUrl(type, d, field) {
        if (type == "histogram") {
            return "index.php?"+
                             "module=APIAccess"+
                             "&method=SessionGraphs.getSessionDataHistogram"+
                             "&session_date="+encodeURIComponent(d)+
                             "&field="+field;
        } else {
            return "index.php?"+
                             "module=APIAccess"+
                             "&method=SessionGraphs.getSessionDataField"+
                             "&session_date="+encodeURIComponent(d)+
                             "&field="+field;
        }
    }

    function getOptions(type, graph)
    {
        var options = jQuery.extend(true, [], jqplot_options);

        options.title          = graph.title;
        options.seriesDefaults = graph.seriesDefaults;

        if (type == 'histogram') {
            options.axes.xaxis = graph.yaxis;
            options.axes.yaxis.label = 'Quantity';
        } else {
            options.axes.yaxis = graph.yaxis;
        }

        return options;
    }



    $("select").change(function () {
        var id       = $(this).attr('id');
        var graph_id = id.substring(7);

        /* Request all the AJAX data and draw the graphs */
        for (var i in graphs) {
            if (graphs[i].id == graph_id) {
                    
                $.ajax({
                    url: getUrl($(this).val(), session_date, graphs[i].field),
                    method: 'GET',
                    dataType: 'json',
                    graph : graphs[i],
                    graph_type : $(this).val(),
                    success: function (series, textStatus, XMLHttpRequest) {
                        var options = getOptions(this.graph_type, this.graph);
                        $.jqplot(this.graph.id, [series], options).replot();
                    }
                });
            }
        }
 
        /* Time      */
        /* Distance  */
        /* Histogram */

        /* TODO: Use functions that are also called in the initialisation */

        /* TODO: Update the graph to show a spinning ajax icon */

        /* TODO: Grab the data via an ajax call */
    }).trigger('change');

});


