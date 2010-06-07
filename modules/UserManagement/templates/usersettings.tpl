{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Settings</title>
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
<h1>Settings</h1>
</center>

<div id="settings">

{if $form_data.errors}
<div id="settings_error">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div>
{/if}

{if $AccessErrorString}
<div id="adduser_error"><strong>Error</strong>: {$AccessErrorString}<br /></div>
{/if}

<form {$form_data.attributes}>
	<p>
		<label>Maximum Heart Rate:<br />
		<input type="text" name="usersettings_maxhr" id="usersettings_maxhr" class="input" value="" size="20" tabindex="10" /></label>
	</p>

	<p class="submit">
		<input type="submit" value="Update" tabindex="100" />
	</p>
</form>

{include file="templates/footer.tpl"}
