var SVNREVISION = '$Revision$';
/* jquery 1.4.2 based */
$().ready(function()
{
	BL.sizeStructure();
	BL.chat.init();
});
$(window).resize(function()
{
	BL.sizeStructure();
});

/* nameSpace BL */
if(!console){
	var console = {
		log:function(){},
		info:function(){},
		dir:function(){}
	}
}
var BL = {};

BL.sizeStructure = function()
{
	iHeight = document.body.clientHeight - document.getElementById('top').clientHeight -2;
	console.info(iHeight);
	$('#forum,#core,#frienBar').height(iHeight);
	$('#chatList').height(iHeight - 200);
};
