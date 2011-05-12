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

{if isset($form_data)}
    {include file="default/templates/genericForm.tpl"}
{/if}

</div>

</body>
</html>
