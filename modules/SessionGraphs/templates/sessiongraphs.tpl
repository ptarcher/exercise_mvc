{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Session Graphs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	{postEvent name="template_js_import"}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.flot.js"></script>
    <script type="text/javascript">session_date="{$session_date}";</script>

    <!-- Open street map -->
    <!-- script src="http://www.openlayers.org/api/OpenLayers.js"></script-->
    <!-- script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script-->
    <!-- script src="modules/SessionGraphs/templates/openstreetmap.js"></script-->

    <!-- Google Maps -->
    <!-- TODO: Get my own real google API key -->
    <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAq5ghvt61uNSMQzLcUm8S2hSrfmvszBws5YN7KOyhHHsUUsEUXRQUGOlxw_FIRO1-0Ch9dZrMj-NpXQ"></script>
    <script src="modules/SessionGraphs/templates/loadgpx.4.js" type="text/javascript"></script>
    <script src="modules/SessionGraphs/templates/googlemaps.js" type="text/javascript"></script>

    <script type="text/javascript" src="modules/SessionGraphs/templates/sessiongraphs.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Exercise data</h1>
<div id="placeholder" style="width:600px;height:300px;"></div>
<br/>
<div style="width:600px; height:600px" id="map"></div>
</center>

{include file="templates/footer.tpl"}
