{extends file="templates/layout.tpl"}

{block name=title}Sessions &rsaquo; {$session_date}{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="modules/SessionGraphs/templates/expand.css" media="screen" />
{/block}

{block name=javascript}
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
{/block}

{block name=body}
<center>
<h1>Exercise data</h1>

<table class="sessiongraphs">
<tr>
    <td>
        {include file="SessionGraphs/templates/table_session.tpl"}
        {include file="SessionGraphs/templates/table_zones.tpl"}
        {include file="SessionGraphs/templates/table_laps.tpl"}
        {include file="SessionGraphs/templates/table_climbs.tpl"}
    </td>
    <td>
        {include file="SessionGraphs/templates/table_graphs.tpl"}
    </td>
</tr>

</center>
{/block}
