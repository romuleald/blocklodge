var SVNREVISION = '$Revision$';
/* jquery 1.4.2 based */


$().ready(function()
{
	BL.sizeStructure();
	BL.user.init();
	//BL.chat.init();
	document.oldTitle = document.title;
});

$(window).resize(function()
{
	BL.sizeStructure();
});

$(window).unload(function()
{
	$.cookie('user',$('#user').val(),{ expires: 7});
});

$(window).bind('blur', function(e){
	BL.away.bIsAway = true;
	BL.away.iSince = new Date().getTime();
});

$(window).bind('focus', function(e){
	BL.away.bIsAway = false;
	BL.chat.obj.iUnreadMsg = 0;
	BL.flashTitle.reset();
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
	iHeight = $(document.body).height() - document.getElementById('top').clientHeight - 20;
	//console.info(iHeight);
	$('#forum,#core,#frienBar').height(iHeight);
	$('#chatList').height(iHeight - 200);
};
