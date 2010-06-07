{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Users</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.js"></script>
</head>

{include file="default/templates/menu.tpl"}

<center>
<h1>Users</h1>
</center>

<!-- draw the table -->
<table class="users" border=1 cellpadding="10">
    <thead>
    <tr>
        <th>UserID</th>
        <th>Coach</th>
        <th>Athlete</th>
        <th>Superuser</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$users key=i item=user}
    <tr>
        <td id="userid">{$user.userid}</td>
        <td id="coach">{$user.coach}</td>
        <td id="athlete">{$user.athlete}</td>
        <td id="superuser">{$user.superuser}</td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

{include file="templates/footer.tpl"}
