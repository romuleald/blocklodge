var SVNREVISION = '$Revision';
/* jquery 1.4.2 based */
$().ready(function()
{
	BL.sizeStructure();
});
$(window).resize(function()
{
	BL.sizeStructure();
});

/* nameSpace BL */

var BL = {};

BL.sizeStructure = function()
{
	iHeight = document.body.clientHeight - document.getElementById('top').clientHeight -2;
	console.info(iHeight);
	$('#forum,#core,#frienBar').height(iHeight);
};
