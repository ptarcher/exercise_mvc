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
        $(this).siblings().find('li').removeClass('hidden');

        /* Position the li elements absolutely below the first ul */
        /* Get the position of the first li a */
        var root     = $('#main-nav > ul');
        var root_pos = root.position();

        /* Add 50px to the height */
        $(this).siblings().find('li').css('top',  root_pos.top+50);

        /* Draw each of the elements each with an increasing width of 50 px */
        $(this).siblings().find('li').each(function (idx, val) {
                $(this).css('left', root_pos.left+idx*100);
            }
        );
	},
	
	outMainLI: function (e)
	{
        //$(this).siblings().find('li').addClass('hidden');
	},
	
	onClickLI: function (e)
	{
        alert('Handler for .click() called.');

        /* Notify the browser we have handled the click */
		return false;
	},

	init: function()
	{
        $('#main-nav .level1>a').hover(this.overMainLI, this.outMainLI);
        $('#main-nav .level1>a').click(this.onClickLI);
	},

	loadFirstSection: function()
	{
		//var self=this;

        /*
        $('li:first').click()
                     .each();
        */
	}
};

$(document).ready( function(){
    coreMenu = new menu();
    coreMenu.init();
    //coreMenu.loadFirstSection();
});
