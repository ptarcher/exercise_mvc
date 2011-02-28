{extends file="templates/layout.tpl"}

{block name=title}Plans{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="Module/Plans/templates/plans.css" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="Module/Plans/templates/plans.js"></script>
{/block}

{block name=body}
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
        <th>Week Date</th>
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
        <td id="period"      class="editable">{$plan.period}</td>
        <td id="description" class="editable">{$plan.description}</td>       
        <td id="comment"     class="editable">{$plan.comment}</td>       
        <td><center><img src="themes/default/images/edit.png"   class="editPlan"   id="row{$i}" alt="" /></center></td>
        <td><center><img src="themes/default/images/remove.png" class="deletePlan" id="row{$i}" alt="" /></center></td>
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
{/block}
