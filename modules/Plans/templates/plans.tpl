{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Plans</title>
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
    <script type="text/javascript" src="modules/Plans/templates/plans.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Exercise Plans</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="plans" cellspacing="1">
    <thead>
    <tr>
{if $coach}
        <th>User</th>
{/if}
        <th>Date</th>
        <th>Period</th>
        <th>Description</th>
        <th>Comments</th>
{literal}
        <th class="{sorter: false}"> </th>
        <th class="{sorter: false}"> </th>
{/literal}
    </tr>
    </thead>
    <tbody>
    {foreach from=$plans key=i item=plan}
    <tr id="row{$i}">
{if $coach}
        <td>{$plan.userid}</td>
{/if}
        <td id="date"><input type="hidden" id="week_date" value="{$plan.week_date}" /><a href="{url module=Plans action=viewDaily week_date=$plan.week_date|escape:url}">{$plan.week_date}</a></td>
        <td id="period"      class="editableSession">{$plan.period}</td>
        <td id="description" class="editableSession">{$plan.description}</td>       
        <td id="comment"     class="editableSession">{$plan.comments}</td>       
        <td><center><img src='themes/default/images/edit.png' class="editPlan" id="row{$i}" alt="" /></center></td>
        <td><center><img src='themes/default/images/remove.png' class="deletePlan" id="row{$i}" alt="" /></center></td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="8"><center>No Plans Found</center></td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

<!-- TODO: Just add a blank row to the table to allow direct input all the time  -->
<div class="addPlan"><a href="#"><img src='themes/default/images/add.png' alt="" />Add Week Plan</a></div>
</div>

{include file="templates/footer.tpl"}
