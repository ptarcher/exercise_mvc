<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- End Header -->

<head>
    <title>Bike &rsaquo; {block name=title}{/block}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- CSS -->
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
    {block name=css}{/block}

    <!-- Javascript -->
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="themes/menu.js"></script>
    {block name=javascript}{/block}

    <!-- Head -->
    {block name=head}{/block}
</head>

<body>
<div id="page-container">
{include file="default/templates/menu.tpl"}

    <header>
    <div id="header">
        <h1><img src="" alt="Bike"></h1>
    </div> 
    </header> <!-- header -->

    <div id="alerts">
    </div> 

    <div id="sidebar">
        <div class="padding">
            {block name=sidebar}{/block}
        </div> <!-- padding -->
    </div> <!-- sidebar -->

    <div id="content">
        <div class="padding">
            {block name=body}{/block}
        </div> <!-- padding -->
    </div> <!--content -->

    <footer>
    <div id="footer">
        <div id="altnav">
            <a href="#">a link</a> - 
            <a href="#">a link</a>
        </div> <!-- altnav -->
        Copyright Bike - Your bike management system<br>

        Bike is an open source project the code can be found on <a href="https://github.com/ptarcher/exercise_mvc">GitHub</a><br>

        Bike is a deriative of <a href="http://www.piwik.org">Piwik</a>
    </div> 
    </footer> <!-- footer -->

</div> <!-- page-container -->

</body>
</html>

