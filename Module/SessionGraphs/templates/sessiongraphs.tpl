{extends file="templates/layout.tpl"}

{block name=title}Sessions &rsaquo; {$session_date}{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="Module/SessionGraphs/templates/expand.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript">var session_date="{$session_date}";</script>

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

{if $InternetIsConnected && $MapData == 'openstreetmap'}
    <!-- Open street map -->
    <script type="text/javascript">MapData = 'openstreetmap';</script>
    <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <script src="Module/SessionGraphs/templates/openstreetmap.js"></script>
{elseif $InternetIsConnected && $MapData == 'google'}
    <!-- Google Maps -->
    <script type="text/javascript">MapData = 'google';</script>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAv9aTDwE6fauiAWoMtxkR-xQoOxTV2asWv1a0V0ChKTrtRJGD1xQTcMVrKXToSqugCGAfdT9Vz1YNPA" type="text/javascript"></script>

    <script src="Module/SessionGraphs/templates/loadgpx.4.js" type="text/javascript"></script>
    <script src="Module/SessionGraphs/templates/googlemaps.js" type="text/javascript"></script>
{else}
    <script type="text/javascript">MapData = '';</script>
{/if}

    <script type="text/javascript" src="Module/SessionGraphs/templates/sessiongraphs.js"></script>
{/block}

{block name=body}
<h2>Exercise data</h2>

{include file="SessionGraphs/templates/table_graphs.tpl"}

{/block}

{block name=sidebar}
{include file="SessionGraphs/templates/table_session.tpl"}
{include file="SessionGraphs/templates/table_zones.tpl"}
{include file="SessionGraphs/templates/table_laps.tpl"}
{include file="SessionGraphs/templates/table_climbs.tpl"}
{/block}
