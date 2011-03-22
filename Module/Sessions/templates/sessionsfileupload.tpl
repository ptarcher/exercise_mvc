{extends file="templates/layout.tpl"}

{block name=title}Upload session{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="Module/Sessions/templates/sessions.css" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="themes/menu_new.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>
    <script type="text/javascript" src="Module/Sessions/templates/session_upload.js"></script>
    <script type="text/javascript">
        var session_timestamp="{$session_timestamp}";
        var plan_date        ="{$planned.timestamp}";
    </script>
{/block}

{block name=body}
<h2>Upload Exercise Sessions</h2>

<!-- upload file -->
<div class="form">
{if $form_data.errors}
<div id="upload_error">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div> <!-- upload_error -->
{/if}

{if $UploadStatusMsg}
<div id="upload_status">
<strong>{$UploadStatus}</strong>: {$UploadStatusMsg}<br />
</div> <!-- upload_status -->
{/if}

{if $planned}
<div id="upload_question">
Is this session ({$session_timestamp})the planned exercise session for {$planned.timestamp}? <a href="#" id="planned_yes">Yes</a><br />
</div> <!-- upload_question -->
{/if}

<form {$form_data.attributes}>
	<p>
		<label>File:<br />
		<input type="file" name="form_upload" id="form_upload" class="input" value="" size="20" tabindex="1" /></label>
	</p>

	<p class="submit">
		<input type="submit" value="Upload" tabindex="2" />
	</p>
</form>

</div> <!-- form -->
{/block}
