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
<!-- upload file -->
<div class="form">
<h1>Upload Exercise Sessions</h1>
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

<form {$form_data.attributes}>
    <label>
        <span>File</span>
        <input type="file" name="form_upload" id="form_upload" class="input" value="" size="20" tabindex="1" />
    </label>

    <input type="submit" value="Upload" tabindex="2" />
</form>

</div> <!-- form -->
{/block}
