{extends file="templates/layout.tpl"}

{block name=title}Dash Board{/block}

{block name=css}
    <link rel="stylesheet" type="text/css" href="Module/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="themes/default/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/superfish/css/superfish.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/dateplustimepicker/themes/default/jquery-dateplustimepicker.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/addons/pager/jquery.tablesorter.pager.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/date.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>

    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/dateplustimepicker/jquery-dateplustimepicker.js"></script>
    <script type="text/javascript" src="libraries/javascript/superfish/js/superfish.js"></script>
    <script type="text/javascript" src="themes/menu.js"></script>
    <script type="text/javascript" src="Module/DashBoard/templates/dashboard.js"></script>
{/block}

{block name=body}
<center>
<h1>Dash Board</h1>
</center>

<div id="week_of_year"></div>
<div id="monday"></div>
<div id="sunday"></div>

{/block}

