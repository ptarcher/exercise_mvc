function menu()
{
	this.param = {};
}

menu.prototype =
{	
	overMainLI: function (e)
	{
        /* Hide any others */
        $('#main-nav .level2').addClass('hidden');

        /* Show the menu */
        $(this).siblings().removeClass('hidden');
	},
	
	outMainLI: function (e)
	{
	},
	
	init: function()
	{
        $('#main-nav .level1>a').hover(this.overMainLI, this.outMainLI);
	},

	loadFirstSection: function()
	{
	}
};

$(document).ready( function(){
    coreMenu = new menu();
    coreMenu.init();
    coreMenu.loadFirstSection();
});
