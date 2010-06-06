{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Sessions</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
    <link rel="stylesheet" type="text/css" href="modules/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
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
        <th> </th>
        <th> </th>
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
        <th>{$session.userid|escape}</th>
    {/if}
        <td id="date"><input type="hidden" id="session_date" value="{$session.session_date}" /><a href="{url module=SessionGraphs session_date=$session.session_date|escape:url}">{$session.session_date|escape}</a></td>
        <td id="type"        class="editableSession">{$session.type_short|escape}</td>
        <td id="description" class="editableSession">{$session.description|escape}</td>       
        <td id="duration"    class="editableSession">{$session.duration|escape}</td>       
        <td id="distance"    class="editableSession">{$session.distance|escape}</td>       
        <td id="avg_speed"   class="editableSession">{$session.avg_speed|escape}</td>       
        <td id="avg_heartrate" class="editableSession">{$session.avg_heartrate}</td>       
        <td id="comment" class="editableSession">{$session.comment|escape}</td>       
        <td><img src='themes/default/images/edit.png' class="editSession" id="row{$i}" href='#' alt="" /></td>
        <td><img src='themes/default/images/remove.png' class="deleteSession" id="row{$i}" value="delete" alt="" /></td>
    </tr>
    {/foreachelse}
    <tr>
        <td colspan="10">No records</td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

<!-- TODO: Just add a blank row to the table to allow direct input all the time  -->
<div class="addRowSession"><a href="#"><img src='themes/default/images/add.png' alt="" />AddSession</a></div>
</div>

{include file="templates/footer.tpl"}
