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

    $('.addPlan').click( function () {
            coreHelper.ajaxHideError();
            $(this).toggle();
            $('#noplans').toggle();

            $('<table class="tablesorter" id="new_daily" cellspace="1">'+
                '<tr>'+
                    '<th>Timestamp</th>'+
                    '<td><input type="text" id="timestamp"/> <img src="themes/default/images/ok.png" class="add" /> <img src="themes/default/images/remove.png" class="cancel" /></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Category</th>'+
                    '<td><input type="text" id="category"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Description</th>'+
                    '<td><input type="text" id="description"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Focus</th>'+
                    '<td><input type="text" id="focus"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Duration</th>'+
                    '<td><input type="text" id="duration"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Comment</th>'+
                    '<td><input type="text" id="comment"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Volume</th>'+
                    '<td><input type="text" id="volume"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<th>Intensity</th>'+
                    '<td><input type="text" id="intensity"/></td>'+
                '</tr>'+
              '</table>').appendTo('.placeholder');

            $('#new_daily').keypress(submitPlanOnEnter);
            $('img.add').click( function() {
                    $.ajax(getAddDailyPlanAJAX($('table#new_daily')));
            });
            $('.cancel').click( function() {
                    coreHelper.ajaxHideError();
                    $(this).parents('table').remove();
                    $('.addPlan').toggle();
                    $('#noplans').toggle();
            });
    });
});

function getAddDailyPlanAJAX(table)
{
    var ajaxRequest = coreHelper.getStandardAjaxConf();
    coreHelper.toggleAjaxLoading();

    var parameters = {};
    parameters.module = 'APIAccess';
    parameters.format = 'json';
    parameters.method =  'Plans.addDailyPlan';

    parameters.week_date   = $('#week_date').val();
    parameters.timestamp   = $(table).find('input#timestamp').val();
    parameters.category    = $(table).find('input#category').val();
    parameters.description = $(table).find('input#description').val();
    parameters.focus       = $(table).find('input#focus').val();
    parameters.duration    = $(table).find('input#duration').val();
    parameters.comment     = $(table).find('input#comment').val();
    parameters.volume      = $(table).find('input#volume').val();
    parameters.intensity   = $(table).find('input#intensity').val();

    ajaxRequest.data = parameters;

    return ajaxRequest;
}

function submitPlanOnEnter(e)
{
    var key=e.keyCode || e.which;
    if (key==13)
    {
        $(this).parent().find('.update').click();
        $(this).parent().find('.add').click();
    }
}

