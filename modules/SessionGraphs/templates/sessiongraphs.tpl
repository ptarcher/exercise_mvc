{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Sessions &rsaquo; {$session_date}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	{postEvent name="template_js_import"}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript">var session_date="{$session_date}";</script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>

    <!-- BEGIN: jqplot -->
    <!--[if IE]><script src="libraries/javascript/jqplot/excanvas.min.js"></script><![endif]-->
    <link rel="stylesheet" type="text/css" href="libraries/javascript/jqplot/jquery.jqplot.css" />
    <script type="text/javascript" src="libraries/javascript/jqplot/jquery.jqplot.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.highlighter.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasThresholdLinesRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.cursor.js"></script>
    <!-- END: jqplot -->

    <!-- Open street map -->
    <!-- script src="http://www.openlayers.org/api/OpenLayers.js"></script-->
    <!-- script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script-->
    <!-- script src="modules/SessionGraphs/templates/openstreetmap.js"></script-->

    <!-- Google Maps -->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAv9aTDwE6fauiAWoMtxkR-xRUnSFVdNCq8_C9uprN1AKVsiEqDBQ31BXtoxNUK3ETgRsjnDg0vbJzjg" type="text/javascript"></script>

    <script src="modules/SessionGraphs/templates/loadgpx.4.js" type="text/javascript"></script>
    <script src="modules/SessionGraphs/templates/googlemaps.js" type="text/javascript"></script>

    <script type="text/javascript" src="modules/SessionGraphs/templates/sessiongraphs.js"></script>

    <!-- Accordian -->
    {literal}
    <style type="text/css">
    .expand {
       border-bottom: solid 1px #c4c4c4;
       background: #e9e7e7
    }
    .expand h3 {
        background: #e9e7e7 url(themes/default/images/arrow-square.gif) no-repeat right -51px;
        padding: 7px 15px;
        margin: 0;
        font: bold 120%/100% Arial, Helvetica, sans-serif;
        border: solid 1px #c4c4c4;
                border-bottom: none;
        cursor: pointer;
    }
    .expand h3:hover {
        background-color: #e3e2e2;
    }
    .expand h3.active {
        background-position: right 5px;
    }
    .expand p {
        background: #f7f7f7;
        margin: 0;
        padding: 10px 15px 20px;
                 border-left: solid 1px #c4c4c4;
                 border-right: solid 1px #c4c4c4;
    }
    </style>
    {/literal}
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Exercise data</h1>

<table class="sessiongraphs">
<tr>
    <td>
        <div class="expand">
            <h3>Overall</h3>
            <!-- Session details -->
            <table>
            <tbody>
            {foreach from=$session key=i item=field}
            <tr>
                <td>{$field.label}:</td>
                <td><input type="hidden" id="{$field.id}" value="{$field.value}">{$field.value} {$field.units}</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />

        <!-- Session Zones -->
        <div class="expand">
            <h3>Zones</h3>
            <table class="tablesorter" id="zones">
            <thead>
            <tr>
                <th>Zone</th>
                <th>Length</th>
            </thead>
            <tbody>
            {foreach from=$zones key=label item=zone}
            <tr>
                <td>{$zone.zone}:</td>
                <td>{$zone.length}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=2>No zoneslaps found.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />

        <!-- Session laps, expandable -->
        <div class="expand">
            <h3>Laps</h3>
            <table class="tablesorter" id="laps">
            <thead>
            <tr>
                <th>Lap Num</th>
                <th>Duration</th>
                <th>Distance</th>
                <th>Avg Speed</th>
                <th>Max Speed</th>
                <th>Avg Heartrate</th>
                <th>Max Heartrate</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$laps key=i item=lap}
            <tr>
                <td>{$lap.lap_num}</td>
                <td>{$lap.duration}</td>
                <td>{$lap.distance}</td>
                <td>{$lap.avg_speed}</td>
                <td>{$lap.max_speed}</td>
                <td>{$lap.avg_heartrate}</td>
                <td>{$lap.max_heartrate}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=7>No laps found.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />

        <!-- Climbs, expandable -->
        <div class="expand">
            <h3>Climbs</h3>
            <table class="tablesorter" id="climbs">
            <thead>
            <tr>
                <th>Climb Num</th>
                <th>Category</th>
                <th>Duration</th>
                <th>Distance</th>
                <th>Altitude</th>
                <!--th>Avg Speed</th-->
                <!--th>Avg Heartrate</th-->
                <!--th>Max Heartrate</th-->
                <th>Avg Gradient</th>
                <th>Max Gradient</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$climbs key=i item=climb}
            <tr>
                <td><a href="{url module=SessionGraphs action=viewClimbs session_date=$session_date|escape:url climb_num=$climb.climb_num|escape:url}">{$climb.climb_num}</a></td>
                <td>{$climb.category}</td>
                <td>{$climb.duration}</td>
                <td>{$climb.total_distance}</td>
                <td>{$climb.total_climbed}</td>
                <!--td>{$climb.avg_speed}</td-->
                <!--td>{$climb.avg_heartrate}</td-->
                <!--td>{$climb.max_heartrate}</td-->
                <td>{$climb.gradient_avg}</td>
                <td>{$climb.gradient_max}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=7>No climbs.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
    </td>
    <td>
        <div class="expand">
            <h3>Location</h3>
            <div style="margin-top:20px; margin-left:20px; width:800px; height:640px" id="map"></div>
        </div>
        <br />

        <div class="expand">
            <h3>Speed</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_speed"></div>
        </div>
        <br />

        <div class="expand">
            <h3>Heart Rate</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_heartrate"></div>
        </div>
        <br />

        <!--div class="expand">
            <h3>Speed</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_distance"></div>
        </div>
        <br /-->

        <div class="expand">
            <h3>Altitude</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_altitude"></div>
        </div>
        <br />

        <div class="expand">
            <h3>Gradient</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_gradient"></div>
        </div>
        <br />


        <div class="expand">
            <h3>Cadence</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_cadence"></div>
        </div>
        <br />

        <div class="expand">
            <h3>Power</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_power"></div>
        </div>
        <br />

        <div class="expand">
            <h3>Temperature</h3>
            <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="graph_temperature"></div>
        </div>
        <br />
            </div>
    </td>
</tr>

</center>


{include file="templates/footer.tpl"}
