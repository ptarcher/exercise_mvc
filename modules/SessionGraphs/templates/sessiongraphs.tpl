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
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Exercise data</h1>
<div style="margin-top:20px; margin-left:20px; width:800px; height:640px" id="map"></div>
<br/>

<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="speed"></div>
<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="heartrate"></div>
<!--div class="jqplot" style="margin:20px;width:800px;height:240px;" id="distance"></div-->
<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="altitude"></div>
<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="cadence"></div>
<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="power"></div>
<div class="jqplot" style="margin:20px;width:800px;height:240px;" id="temperature"></div>
</center>


{include file="templates/footer.tpl"}
