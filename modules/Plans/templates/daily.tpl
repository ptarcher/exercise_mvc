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

    <!-- BEGIN: jqplot -->
    <!--[if IE]><script src="libraries/javascript/jqplot/excanvas.min.js"></script><![endif]-->
    <link rel="stylesheet" type="text/css" href="libraries/javascript/jqplot/jquery.jqplot.css" />
    <script type="text/javascript" src="libraries/javascript/jqplot/jquery.jqplot.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.barRenderer.js"></script>
    <!-- END: jqplot -->

    <script type="text/javascript" src="modules/Plans/templates/daily.js"></script>
    <script type="text/javascript">
    var graphs = [
{assign var="plan_id" value="0"}
{foreach from=$plans key=i item=plan}
{literal}
        {
{/literal}
            id:"volume{$plan_id}",
            series:[[{$plan.volume}, 1]],
            color:'#4BB2C5',
{literal}
        },
        {
{/literal}
            id:"intensity{$plan_id}",
            series:[[{$plan.intensity}, 1]],
            color:'#EAA228',
{literal}
        },
{/literal}
{math assign="plan_id" equation="id+1" id=$plan_id}
{/foreach}
    ];
    </script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Daily Exercise Plans</h1>
</center>

<!-- draw the table -->
{assign var="plan_id" value="0"}
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
        <td><b>{$plan.timestamp}</b></td>
    </tr>
    <tr>
        <th>Category</th>
        <td>{$plan.category}</td>
    </tr>
    <tr>
        <th>Description</th>
        <td>{$plan.description}</td>
    </tr>
    <tr>
        <th>Focus</th>
        <td>{$plan.focus}</td>
    </tr>
    <tr>
        <th>Duration</th>
        <td>{$plan.duration}</td>
    </tr>
    <tr>
        <th>Comment</th>
        <td>{$plan.comment}</td>
    </tr>
    <tr>
        <th>Volume</th>
        <!--td>{$plan.volume}</td-->
        <td><div id="volume{$plan_id}" style="margin:00px;width:600px;height:50px"></div></td>
    </tr>
    <tr>
        <th>Intensity</th>
        <!--td>{$plan.intensity}</td-->
        <td><div id="intensity{$plan_id}" style="margin:00px;width:600px;height:50px"></div></td>
    </tr>
</table>

{math assign="plan_id" equation="id+1" id=$plan_id}
{foreachelse}
<table class="tablesorter" id="plans" cellspacing="1">
    <tr>
        <td colspan="2"><center>No Plans Found</center></td>
    </tr>
</table>
{/foreach}

<!-- end the table -->

{include file="templates/footer.tpl"}
