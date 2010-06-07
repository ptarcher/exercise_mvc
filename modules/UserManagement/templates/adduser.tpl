{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Add User</title>
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
<h1>Add User</h1>
</center>

<div id="login">

{if $form_data.errors}
<div id="login_error">	
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
		<label>Login:<br />
		<input type="text" name="adduserform_login" id="adduserform_login" class="input" value="" size="20" tabindex="10" /></label>
	</p>

	<p>
		<label>Password:<br />
		<input type="password" name="adduserform_password" id="adduserform_password" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p>
		<label>Coach:<br />
        {html_radios name='adduserform_coach' options=$coach_types selected=$coach_selected separator=' ' tabindex="30"}
	</p>
	<p>
		<label>Athlete:<br />
        {html_radios name='adduserform_athlete' options=$athlete_types selected=$athlete_selected separator=' ' tabindex="40"}
	</p>
    <p>
		<label>Type: <br />
        {html_options name='adduserform_usertype' options=$usertype_types selected=$usertype_selected separator=' ' tabindex="50"}
	</p>


	<p class="submit">
		<input type="submit" value="Create" tabindex="100" />
	</p>
</form>

{include file="templates/footer.tpl"}
