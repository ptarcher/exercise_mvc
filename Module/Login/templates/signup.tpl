{include file="Login/templates/header.tpl"}

<div id="login">

{if $form_data.errors}
<div id="errors">	
	{foreach from=$form_data.errors item=data}
		<strong>Error</strong>: {$data}<br />
	{/foreach}
</div>
{/if}

{if $errorMessage}
<div id="error"><strong>Error</strong>: {$errorMessage}<br /></div>
{/if}

<form {$form_data.attributes}>
	<p>
		<label>Username:<br />
		<input type="text" name="form_login" id="form_login" class="input" value="" size="20" tabindex="10" required /></label>
	</p>

	<p>
		<label>Email:<br />
		<input type="email" name="form_email" id="form_email" class="input" value="" size="20" tabindex="20" required /></label>
	</p>

	<p>
		<label>Password:<br />
		<input type="password" name="form_password" id="form_password" class="input" value="" size="20" tabindex="30" required /></label>
	</p>
	<p>
		<label>Confirm Password:<br />
		<input type="password" name="form_passwordconfirm" id="form_password" class="input" value="" size="20" tabindex="40" required /></label>
	</p>

	<p class="submit">
		<input type="submit" value="Sign-Up" tabindex="100" />
	</p>
</form>

</div>

</body>
</html>
