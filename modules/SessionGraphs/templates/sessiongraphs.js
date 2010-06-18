$(function () {
    // Call the Open Streetmap API
    //OSM_Init();
    // Call the googlemaps API
    GoogleMaps_Init();

    // jqplot
    var jqplot_options = {
        title:session_date,
        seriesDefaults:{showMarker: false, shadow:false},
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

    var graphs = [
        {
            id    : 'speed',
            title : 'Speed',
            field : 'speed',
            yaxis_label : 'Speed (km/h)',
        },

        {
            id    : 'heartrate',
            title : 'Heart Rate',
            field : 'heartrate',
            yaxis_label : 'Heart Rate (bpm)',
        },

        {
            id    : 'distance',
            title : 'Distance',
            field : 'distance',
            yaxis_label : 'Distance (km)',
        },

        {
            id    : 'altitude',
            title : 'Altitude',
            field : 'altitude',
            yaxis_label : 'Elevation (m)',
        },

        {
            id    : 'cadence',
            title : 'Cadence',
            field : 'cadence',
            yaxis_label : 'Cadence (rpm)',
        },

        {
            id    : 'power',
            title : 'Power',
            field : 'power',
            yaxis_label : 'Power (watts)',
        },

        {
            id    : 'temperature',
            title : 'Temperature',
            field : 'temperature',
            yaxis_label : 'Temperature (C)',
        },
    ];

    /* Request all the AJAX data and draw the graphs */
    for (var i in graphs) {
        $.ajax({
            url: "index.php?"+
                            "module=APIAccess"+
                            "&method=SessionGraphs.getSessionDataField"+
                            "&session_date="+encodeURIComponent(session_date)+
                            "&field="+graphs[i].field,
            method: 'GET',
            dataType: 'json',
            graph : graphs[i],
            success: function (series, textStatus, XMLHttpRequest) {
                var options = jqplot_options;
                options.title = this.graph.title;
                options.axes.yaxis.label = this.graph.yaxis_label;
                $.jqplot(this.graph.id, [series], options);
            }
        });
    }
    
    $(".expand h3").addClass("active");
    $(".expand h3").click(function(){
        $(this).next().toggle();
        $(this).toggleClass("active");
    });

});

