{extends file="templates/layout.tpl"}

{block name=title}Dash Board{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="Module/DashBoard/templates/dashboard.css" media="screen" />

<style type="text/css">
#header {
    background-image:url(../images/headers/about.jpg);
}

#main-nav li#DashBoard > a {
    background-position: 0 -100px;
}
</style>
{/block}

{block name=javascript}
    <script type="text/javascript" src="libraries/javascript/date.js"></script>

    <!-- BEGIN: jqplot -->
    <!--[if IE]><script src="libraries/javascript/jqplot/excanvas.min.js"></script><![endif]-->
    <link rel="stylesheet" type="text/css" href="libraries/javascript/jqplot/jquery.jqplot.css" />
    <script type="text/javascript" src="libraries/javascript/jqplot/jquery.jqplot.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.pointLabels.js"></script>

    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>

    <script type="text/javascript" src="libraries/javascript/jqplot/plugins/jqplot.barRenderer.js"></script>

    <!-- END: jqplot -->

    <script type="text/javascript" src="Module/DashBoard/templates/dashboard.js"></script>
{/block}

{block name=body}
<h2><img src="" alt="Dashboard"></h2>

<div class="jqplot" style="margin:20px;width:620px;height:240px;"  id="graphs_plans"></div>

<span class="plans_back">back</span>
<span class="plans_forward">forward</span>
<div style="margin:20px;width:620px;height:240px;" id="details_plans"></div>

{/block}

{block name=sidebar}
Hi there, I am in the sidebar
{/block}
