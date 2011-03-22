function menu()
{
	this.param = {};
}

menu.prototype =
{	
	overMainLI: function ()
	{
		$(this).siblings().removeClass('hidden');
	},
	
	outMainLI: function ()
	{
		$(this).siblings().addClass('hidden');
	},
	
	onClickLI: function ()
	{
		return false;
	},

	init: function()
	{
        var link = $('#main-nav .level1');
        $('#main-nav .level1').each(function(index) {
                $(this).hover(
                    this.overMainLI,
                    this.outMainLI
                );
        });
	},

	loadFirstSection: function()
	{
		var self=this;

        /*
        $('li:first').click()
                     .each();
        */
	}
};

$(document).ready( function(){
    coreMenu = new menu();
    coreMenu.init();
    coreMenu.loadFirstSection();
});
