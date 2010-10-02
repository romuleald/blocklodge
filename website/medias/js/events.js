/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 26 sept. 2010
 * Time: 03:52:26
 * To change this template use File | Settings | File Templates.
 */

$().ready(function()
{
	BL.sizeStructure();
	BL.user.init();
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

$(window).bind('blur', function(){
	BL.away.bIsAway = true;
	BL.away.iSince = new Date().getTime();
});

$(window).bind('focus', function(){
	BL.away.bIsAway = false;
	BL.chat.obj.iUnreadMsg = 0;
	BL.flashTitle.reset();
});

$(document).bind('login', function(){
	BL.chat.init();
});
