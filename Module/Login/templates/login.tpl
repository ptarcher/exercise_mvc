{include file="Login/templates/header.tpl"}

<div id="login">

{if $form_data.errors}
<div id="login_error">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div>
{/if}

{if $AccessErrorString}
<div id="login_error"><strong>Error</strong>: {$AccessErrorString}<br /></div>
{/if}

<form {$form_data.attributes}>
	<p>
		<label>Login:<br />
		<input type="text" name="form_login" id="form_login" class="input" value="" size="20" tabindex="10" required /></label>
	</p>

	<p>
		<label>Password:<br />
		<input type="password" name="form_password" id="form_password" class="input" value="" size="20" tabindex="20" required /></label>
	</p>
	<p class="forgetmenot">
        <label>Remember Me
        <input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> </label>
    </p>
	<input name="form_url" type="hidden" value="{$urlToRedirect}" />
	<p class="submit">
		<input type="submit" value="Login" tabindex="100" />
	</p>
</form>

<p id="nav">
<a href="index.php?module=Login&amp;action=lostPassword&amp;form_url={$urlToRedirect|escape:url}" title="Lost Your Password">Lost Your Password</a> | <a href="index.php?module=Login&amp;action=signup&amp;form_url={$urlToRedirect|escape:url}" title="Sign-up">Sign-up</a>
</p>

</div>

</body>
</html>
