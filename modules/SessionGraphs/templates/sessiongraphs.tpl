{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Session Graphs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	{postEvent name="template_js_import"}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript">session_date="{$session_date}";</script>

    <!-- BEGIN: jqplot -->
    <!--[if IE]><script src="libraries/javascript/jqplot/excanvas.min.js"></script><![endif]-->
    <link rel="stylesheet" type="text/css" href="libraries/javascript/jqplot/jquery.jqplot.css" />
    <script type="text/javascript" src="libraries/javascript/jqplot/jquery.jqplot.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.highlighter.js"></script>
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

<table>
<tr>
    <td>
        <h3>Details</h3>
        <!-- Session details -->
        <table>
        <tr>
            <td>Date:</td>
            <td>{$session.session_date}</td>
        <tr>
        </table>

        <!-- Session laps, expandable -->
        <h3>Laps</h3>
        <table>
        {foreach from=$laps key=i item=lap}
        <tr>
            <td>Lap {$lap.lap_num}:</td>
            <td>{$lap.duration}</td>
        <tr>
        {foreachelse}
        <tr>
            <td colspan=2>No laps found.</td>
        </tr>
        {/foreach}
        </table>
    </td>
    <td>
        <div class="expand">
        <h3>Location</h3>
        <div style="margin-top:20px; margin-left:20px; width:800px; height:640px" id="map"></div>

        <h3>Speed</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="speed"></div>

        <h3>Heart Rate</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="heartrate"></div>

        <!--h3>Speed</h3-->
        <!--div class="jqplot" style="margin:20px;width:800px;height:240px;" id="distance"></div-->

        <h3>Altitude</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="altitude"></div>

        <h3>Cadence</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="cadence"></div>

        <h3>Power</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="power"></div>

        <h3>Temperature</h3>
        <div class="jqplot" style="margin:20px;width:800px;height:240px;" id="temperature"></div>
        </div>
    </td>
</tr>

</center>


{include file="templates/footer.tpl"}
