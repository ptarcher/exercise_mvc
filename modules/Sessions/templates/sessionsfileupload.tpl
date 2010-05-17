{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Sessions</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
    <link rel="stylesheet" type="text/css" href="modules/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

<center>
<h1>Upload Exercise Sessions</h1>
</center>

<!-- upload file -->
<div id="login">
{if $form_data.errors}
<div id="upload_error">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div>
{/if}

{if $AccessErrorString}
<div id="upload_error"><strong>Error</strong>: {$AccessErrorString}<br /></div>
{/if}

<form {$form_data.attributes}>
	<p>
		<label>File:<br />
		<input type="file" name="form_upload" id="file" class="input" value="" size="20" tabindex="1" /></label>
	</p>

	<p class="submit">
		<input type="submit" value="Upload" tabindex="2" />
	</p>
</form>

</div>
<!-- end of upload file -->

{include file="templates/footer.tpl"}
