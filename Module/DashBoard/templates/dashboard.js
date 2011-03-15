var plot;
var tabs;

function updatePlans(d) 
{
    var monday = d.toString('yyyy-MM-dd');

    // jqplot
    var jqplot_options = {
        title:'Week '+monday+' Plan',
        //stackSeries: true,
        legend: {
            show: true,
            location: 'ne'
        },

        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            showMarker: false, 
            shadow:false,
            /*
            thresholdLines: {
                yValues: [0.0],
                showLabel: true,
            },*/
        },
        series: [
            {label: 'Volume',},
            {label: 'Intensity',},
        ],
        axesDefaults:{
            tickOptions:{formatString:"%d"}, 
            autoscale:true, 
            useSeriesColor:true,
        },
        axes:{
            xaxis:{
                renderer: $.jqplot.CategoryAxisRenderer
            },
            yaxis: {
                padMax:1.3,
                min:0
           }
        },
        cursor: {
            show:true, 
            zoom:true
        },
    };

    /* Grab the data for the plans */
    $.ajax({
        url: "index.php?"+
                        "module=APIAccess"+
                        "&method=Plans.getDailyPlansDetails"+
                        //"&method=Plans.getDailyPlans"+
                        "&order=ASC"+
                        "&week_date="+encodeURIComponent(monday),
        method: 'GET',
        dataType: 'json',
        success: function (daily_plans, textStatus, XMLHttpRequest) {
            /* Create an array of each days intensity level and volume */
            var series = {
                volume:    [],
                intensity: []
            };
            var content = "";
            var today   = new Date();

            /* Create the details */
            days = [
                { div: 'day_monday',
                  day: 'Monday',
                  sessions: new Array(),
                },
                { div: 'day_tuesday',
                  day: 'Tuesday',
                  sessions: new Array(),
                },
                { div: 'day_wednesday',
                  day: 'Wednesday',
                  sessions: new Array(),
                },
                { div: 'day_thursday',
                  day: 'Thursday',
                  sessions: new Array(),
                },
                { div: 'day_friday',
                  day: 'Friday',
                  sessions: new Array(),
                },
                { div: 'day_saturday',
                  day: 'Saturday',
                  sessions: new Array(),
                },
                { div: 'day_sunday',
                  day: 'Sunday',
                  sessions: new Array(),
                },
            ];

             series.volume = [
                ['Mon AM', 0],
                ['Mon PM', 0],
                ['Tue AM', 0],
                ['Tue PM', 0],
                ['Wed AM', 0],
                ['Wed PM', 0],
                ['Thu AM', 0],
                ['Thu PM', 0],
                ['Fri AM', 0],
                ['Fri PM', 0],
                ['Sat AM', 0],
                ['Sat PM', 0],
                ['Sun AM', 0],
                ['Sun PM', 0]];

             series.intensity = [
                ['Mon AM', 0],
                ['Mon PM', 0],
                ['Tue AM', 0],
                ['Tue PM', 0],
                ['Wed AM', 0],
                ['Wed PM', 0],
                ['Thu AM', 0],
                ['Thu PM', 0],
                ['Fri AM', 0],
                ['Fri PM', 0],
                ['Sat AM', 0],
                ['Sat PM', 0],
                ['Sun AM', 0],
                ['Sun PM', 0]];

            /* Update the values to match what is in the plan */
            for (var i in daily_plans) {
                var d = new Date(parseInt(daily_plans[i].epoch));
                var cat;
                var monday_offset = ((d.getDay() + 7) - 1) % 7;

                /* Calculate the category index */
                cat = monday_offset*2;
                if (d.getHours() >= 12) {
                    cat += 1;
                }

                series.volume[cat][1]    = parseFloat(daily_plans[i].volume);
                series.intensity[cat][1] = parseFloat(daily_plans[i].intensity);
            }

            if (!plot) {
                plot = $.jqplot('graphs_plans', 
                        [series.volume,series.intensity], jqplot_options);
            } else {
                plot.series[0].data = series.volume;
                plot.series[1].data = series.intensity;
                //plot.title = 'Week '+monday+' Plan';
                plot.drawSeries({}, 0);
                //plot.drawSeries({}, 1);
            }

            /* Convert the daily_plans into a days and sessions array */
            for (var i in daily_plans) {
                var d = new Date(parseInt(daily_plans[i].epoch));
                var s = new Date(parseInt(daily_plans[i].session_epoch));
                var monday_offset = ((d.getDay() + 7) - 1) % 7;
                var len = days[monday_offset].sessions.length;
                days[monday_offset].sessions[len] = {
                    date:      d.toString('HH:mm'),
                    time:      d.toString('HH:mm'),
                    intensity: daily_plans[i].intensity,
                    volume:    daily_plans[i].volume,
                    duration:  daily_plans[i].duration,
                    session_date: s.toString('HH:mm'),
                    session_time: s.toString('HH:mm'),
                    session_duration: daily_plans[i].session_duration,
                };
            }

            /* Display the tab selections */
            content += '<ul>';
            for (var i in days) {
                content += '<li><a href="#'+days[i].div+'">'+days[i].day+'</a></li>';;
            }
            content += '</ul>';

            /* Generate each days sessions */
            for (var i in days) {
                content += '<div id="'+days[i].div+'">';
                //content += days[i].day;

                /* Draw the headers for the day */
                content += '<table width="100%">'+
                                '<tr>'+
                                    '<th></th>'+
                                    '<th>Date</th>'+
                                    '<th>Time</th>'+
                                    '<th>Intensity</th>'+
                                    '<th>Volume</th>'+
                                    '<th>Duration</th>'+
                                '</tr>';

                /* Draw each of the training sessions */
                for (var j in days[i].sessions) {
                    content += '<tr>'+
                                 '<th>Planned</th>'+
                                 '<td><center>'+days[i].sessions[j].date+'</center></td>'+
                                 '<td><center>'+days[i].sessions[j].time+'</center></td>'+
                                 '<td><center>'+days[i].sessions[j].intensity+'</center></td>'+
                                 '<td><center>'+days[i].sessions[j].volume+'</center></td>'+
                                 '<td><center>'+days[i].sessions[j].duration+'</center></td>'+
                               '</tr>';
                    if (days[i].sessions[j].session_duration) {
                    content += '<tr>'+
                                 '<th>Actual</th>'+
                                 '<td><center>'+days[i].sessions[j].session_date+'</center></td>'+
                                 '<td><center>'+days[i].sessions[j].session_time+'</center></td>'+
                                 '<td></td>'+
                                 '<td></td>'+
                                 '<td><center>'+days[i].sessions[j].session_duration+'</center></td>'+
                               '</tr>';
                    }
                }
                
                content +=  '</table>';
                content += '</div>';
            }

            var selected;
            if (tabs) {
                selected = $(".div#details_plans").tabs("option", "selected");
                selected = ((today.getDay() + 7) - 1) % 7;
                $('div#details_plans').tabs("destroy");
            } else {
                selected = ((today.getDay() + 7) - 1) % 7;
            }

            $('div#details_plans').html(content);
            tabs = $('div#details_plans').tabs()
                                         .tabs('select', selected);

            $(".plans_forward").click(function() {
                var new_date = d.addDays(6);
                updatePlans(new_date);
            });
            $(".plans_back").click(function() {
                var new_date = d.addDays(-7);
                updatePlans(new_date);
            });

        }
    });
}

$(document).ready( function() {
    //var d = Date.parse('monday');
    var d = Date.parse('2010-06-14');
    //var d = Date.parse('2010-12-27');

    updatePlans(d);
});
