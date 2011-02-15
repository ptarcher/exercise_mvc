{extends file="templates/layout.tpl"}

{block name=title}{$week_date}{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="Module/Plans/templates/daily.css" />
{/block}

{block name=javascript}
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

    <script type="text/javascript" src="Module/Plans/templates/daily.js"></script>
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
{/block}

{block name=body}
<center>
<h1>Daily Exercise Plans</h1>
</center>

<input id="week_date" type="hidden" value="{$week_date}"/>

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
        <td class="editable"><b>{$plan.timestamp}</b></td>
    </tr>
    <tr>
        <th>Category</th>
        <td class="editable">{$plan.category}</td>
    </tr>
    <tr>
        <th>Description</th>
        <td class="editable">{$plan.description}</td>
    </tr>
    <tr>
        <th>Focus</th>
        <td class="editable">{$plan.focus}</td>
    </tr>
    <tr>
        <th>Duration</th>
        <td class="editable">{$plan.duration}</td>
    </tr>
    <tr>
        <th>Comment</th>
        <td class="editable">{$plan.comment}</td>
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
<table class="tablesorter" id="noplans" cellspacing="1">
    <tr>
        <td colspan="2"><center>No Plans Found</center></td>
    </tr>
</table>
{/foreach}

<!-- end the table -->

<div class="placeholder"></div>

<div class="addPlan"><a href="#"><img src='themes/default/images/add.png' alt="" />Add Daily Plan</a></div>
</div>

{/block}
