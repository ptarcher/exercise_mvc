{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Sessions</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
    <link rel="stylesheet" type="text/css" href="modules/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="themes/default/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/superfish/css/superfish.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/dateplustimepicker/themes/default/jquery-dateplustimepicker.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>

    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/dateplustimepicker/jquery-dateplustimepicker.js"></script>
    <script type="text/javascript" src="libraries/javascript/superfish/js/superfish.js"></script>
    <script type="text/javascript" src="themes/menu.js"></script>
    <script type="text/javascript" src="modules/Sessions/templates/sessions.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Exercise Sessions</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="editSessions" cellspacing="1">
    <thead>
    <tr>
{if $coach}
        <th>User</th>
{/if}
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th>Duration</th>
        <th>Distance</th>
        <th>Average Speed</th>
        <th>Average Heart Rate</th>
        <th>Comments</th>
{literal}
        <th class="{sorter: false}"> </th>
        <th class="{sorter: false}"> </th>
{/literal}
    </tr>
    </thead>
    <tbody>
    {* assign var=showSitesSelection value=false *}
    {* assign var=week value="false" *}
    {foreach from=$sessions key=i item=session}
    {if $week != $session.week}
    <tr>
        <th colspan="8">Week 1</th>
        {* assign var=week value=$session.week *}
    </tr>
    {/if}
    <tr id="row{$i}">
    {if $coach}
        <td>{$session.userid}</td>
    {/if}
        <td id="date"><input type="hidden" id="session_date" value="{$session.session_date}" /><a href="{url module=SessionGraphs session_date=$session.session_date|escape:url}">{$session.session_date}</a></td>
        <td id="type"        class="editableSession">{$session.type_short}</td>
        <td id="description" class="editableSession">{$session.description}</td>       
        <td id="duration"    class="editableSession">{$session.duration}</td>       
        <td id="distance"    class="editableSession">{$session.distance}</td>       
        <td id="avg_speed"   class="editableSession">{$session.avg_speed}</td>       
        <td id="avg_heartrate" class="editableSession">{$session.avg_heartrate}</td>       
        <td id="comment" class="editableSession">{$session.comment}</td>       
        <td><center><img src='themes/default/images/edit.png' class="editSession" id="row{$i}" alt="" /></center></td>
        <td><center><img src='themes/default/images/remove.png' class="deleteSession" id="row{$i}" alt="" /></center></td>
    </tr>
    {foreachelse}
    <tr id="norecords">
        <td colspan="11"><center>No records</center></td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

<!-- TODO: Just add a blank row to the table to allow direct input all the time  -->
<div class="addRowSession">
<a href="#"><img src='themes/default/images/add.png' alt="" />AddSession</a>
</div>

{include file="templates/footer.tpl"}
