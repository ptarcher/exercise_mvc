{extends file="templates/layout.tpl"}
{block name=title}Climbs{/block}

{block name=css}
    <link rel="stylesheet" type="text/css" href="modules/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="themes/default/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/superfish/css/superfish.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/dateplustimepicker/themes/default/jquery-dateplustimepicker.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
{/block}

{block name=javascript}
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
{/block}

{block name=body}
<center>
<h1>Climbs</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="edit" cellspacing="1">
    <thead>
    <tr>
{if $coach}
        <th>User</th>
{/if}
        <th>Date</th>
        <th>Name</th>
        <th>Climb num</th>
        <th>Description</th>
        <th>Duration</th>
        <th>Distance</th>
{literal}
        <th class="{sorter: false}"> </th>
        <th class="{sorter: false}"> </th>
{/literal}
    </tr>
    </thead>
    <tbody>
    {* assign var=showSitesSelection value=false *}
    {* assign var=week value="false" *}
    {foreach from=$climbs key=i item=climb}
    {if $week != $session.week}
    <tr>
        <th colspan="8">Week 1</th>
        {* assign var=week value=$climb.week *}
    </tr>
    {/if}
    <tr id="row{$i}">
    {if $coach}
        <td>{$climb.userid}</td>
    {/if}
        <td id="date"><input type="hidden" id="session_date" value="{$climb.session_date}" /><a href="{url module=SessionGraphs session_date=$climb.session_date|escape:url climb_num=$climb.climb_num|escape:url}">{$climb.session_date}</a></td>
        <td id="name"        class="editable">{$climb.name}</td>       
        <td id="clim_num"                    >{$climb.climb_num}</td>       
        <td id="description" class="editable">{$climb.description}</td>       
        <td id="duration"    class="editable">{$climb.duration}</td>       
        <td id="distance"    class="editable">{$climb.distance}</td>       
        <!--td id="avg_speed"   class="editable">{$session.avg_speed}</td>       
        <td id="avg_heartrate" class="editable">{$session.avg_heartrate}</td-->       
        <td><center><img src='themes/default/images/edit.png' class="editClimb" id="row{$i}" alt="" /></center></td>
        <td><center><img src='themes/default/images/remove.png' class="deleteClimb" id="row{$i}" alt="" /></center></td>
    </tr>
    {foreachelse}
    <tr id="noclimbs">
        <td colspan="11"><center>No climbs</center></td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

<!-- TODO: Just add a blank row to the table to allow direct input all the time  -->
<div class="addRowClimb">
<a href="#"><img src='themes/default/images/add.png' alt="" />AddSession</a>
</div>
{/block}
