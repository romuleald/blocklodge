var SVNREVISION = '$Revision$';
/* jquery 1.4.2 based */

/* nameSpace BL */
var BL = {};

BL.sizeStructure = function()
{
	var iHeight = $(document.body).height() - document.getElementById('top').clientHeight - 20;
	//console.info(iHeight);
	$('#forum,#core,#frienBar').height(iHeight);
	$('#chatList').height(iHeight - 200);
};


if(!console){
	var console = {
		log:function(){},
		info:function(){},
		dir:function(){}
	}
}
