$(function () {
    // jqplot
    var jqplot_options = {
        seriesColors: [ "#4BB2C5" ],
        //seriesColors: [ "#EAA228" ],
        //title:"Volume",
        seriesDefaults:{
            renderer: $.jqplot.BarRenderer,
            rendererOptions:{
                barDirection: 'horizontal',
                barPadding:   6,
                barMargin:    15
            },
            showMarker: false, 
            shadow:     false,
        },
        series:[
            {label:'Intensity'}, 
            //{label:'Volume'}, 
        ],
        axes:{
            xaxis:{
                min:0,
                max:100,
            },
            yaxis:{
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks:   [' '],
            },
        },
        //legend: {location:'ne'},
    };

    for (var i in graphs) {
        var options              = jqplot_options;
        options.axes.yaxis.label = graphs[i].yaxis_label;
        options.seriesColors     = [graphs[i].color]
        $.jqplot(graphs[i].id, [graphs[i].series], options);
    }

    //volume    = $.jqplot('volume',    [[[50, 1]]], jqplot_options);
    //intensity = $.jqplot('intensity', [[[70, 1]]], jqplot_options);
});

