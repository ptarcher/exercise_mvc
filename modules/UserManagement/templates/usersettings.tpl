{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Settings</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>

    <!-- BEGIN: jqplot -->
    <!--[if IE]><script src="libraries/javascript/jqplot/excanvas.min.js"></script><![endif]-->
    <link rel="stylesheet" type="text/css" href="libraries/javascript/jqplot/jquery.jqplot.css" />
    <script type="text/javascript" src="libraries/javascript/jqplot/jquery.jqplot.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.barRenderer.js"></script>
    <!-- END: jqplot -->

    <script type="text/javascript" src="modules/Plans/templates/daily.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Settings</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="settings" cellspacing="1">
{foreach from=$settings key=label item=val}
    <tr>
        <th>{$label}:</th>
        <td>{$val}</td>
    </tr>
{foreachelse}
    <tr>
        <td colspan="2"><center>No Settings Found</center></td>
    </tr>
{/foreach}
</table>

<!-- end the table -->

{include file="templates/footer.tpl"}
