{extends file="templates/layout.tpl"}

{block name=title}Upload session{/block}

{block name=css}
    <link rel="stylesheet" type="text/css" href="Module/Sessions/templates/sessions.css" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.js"></script>
{/block}

{block name=body}
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
{/block}
