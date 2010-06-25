{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Daily Plans</title>
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
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Daily Exercise Plans</h1>
</center>

<!-- draw the table -->
{foreach from=$plans key=i item=plan}
<table class="tablesorter" id="plans" cellspacing="1">
{if $coach}
    <tr>
        <th>User</th>
        <td>{$plan.userid}</td>
    </tr>
{/if}
    <tr>
        <th>Time</th>
        <td>{$plan.timestamp}</td>
    </tr>
    <tr>
        <th>Category</th>
        <td>{$plan.category}</td>
    </tr>
    <tr>
        <th>Volume</th>
        <td>{$plan.volume}</td>
    </tr>
    <tr>
        <th>Intensity</th>
        <td>{$plan.intensity}</td>
    </tr>
</table>
{foreachelse}
<table class="tablesorter" id="plans" cellspacing="1">
    <tr>
        <td colspan="2"><center>No Plans Found</center></td>
    </tr>
</table>
{/foreach}

<!-- end the table -->

{include file="templates/footer.tpl"}
