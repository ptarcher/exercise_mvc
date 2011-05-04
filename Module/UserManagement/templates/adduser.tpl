{extends file="templates/layout.tpl"}

{block name=title}Add User{/block}
{include file="templates/header.tpl"}
{block name=css}
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery.js"></script>
{/block}

{block name=body}

<div class="form">
<h1>Add User</h1>

{if $form_data.errors}
<div id="form_error">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div>
{/if}

<form {$form_data.attributes}>
    <label>
        <span>Login</span>
        <input type="text" name="login" id="login" class="input" value="" size="20" tabindex="10" />
    </label>

    <label>
        <span>Password</span>
        <input type="password" name="password" id="password" class="input" value="" size="20" tabindex="20" />
    </label>

    <label>
        <span>Coach</span>
        {html_radios name='coach' options=$coach_types selected=$coach_selected separator=' ' tabindex="30"}
    </label>
    <label>
        <span>Athlete</span>
        {html_radios name='athlete' options=$athlete_types selected=$athlete_selected separator=' ' tabindex="40"}
    </label>

    <label>
        <span>Type</span>
        {html_options name='usertype' options=$usertype_types selected=$usertype_selected separator=' ' tabindex="50"}
    </label>

    <input type="submit" value="Create" tabindex="100" />
</form>

</div > <!-- form-->

{/block}
