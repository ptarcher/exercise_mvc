{extends file="templates/layout.tpl"}

{block name=title}Settings{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="Module/UserManagement/templates/usersettings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="Module/UserManagement/templates/viewBikes.js"></script>
{/block}

{block name=body}
<center>
<h1>Parts</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="settings" cellspacing="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Part</th>
            <th>Description</th>
            <th>Inspection Period</th>
            <th>Inspected</th>
            <th>Replaced</th>
            <th>Withdrawn</th>
{literal}
            <th class="{sorter: false}"></th>
            <th class="{sorter: false}"></th>
{/literal}
        </tr>
    </thead>
    <tbody>
        {foreach from=$parts key=i item=part}
        <tr id="row{$i}">
            <td>{$part.category}</td>
            <td>{$part.part}</td>
            <td>{$part.description}</td>
            <td>{$part.inspection_period_date} or {$part.inspection_period_km} km</td>
            <td>{$part.inspected_date} or {$part.inspected_km} km</td>
            <td>{$part.replaced_date} - {$part.replaced_km}</td>
            <td>{$part.withdrawn_date} at {$part.withdrawn_km} km</td>
            <td><center><img src='themes/default/images/edit.png' class="edit" id="row{$i}" alt="" /></center></td>
            <td><center><img src='themes/default/images/remove.png' class="delete" id="row{$i}" alt="" /></center></td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="9"><center>No Parts Found</center></td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- end the table -->
{/block}

{block name=sidebar}
<h2>Bikes</h2>

{foreach from=$bikes key=i item=bike}
    <div><a href="{url module=UserManagement action=viewBike id=$bike.id|escape:url}">{$bike.type} - {$bike.name}</a></div>
{/foreach}

{/block}
