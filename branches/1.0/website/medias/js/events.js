/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 26 sept. 2010
 * Time: 03:52:26
 * To change this template use File | Settings | File Templates.
 */

$().ready(function()
{
	BL.dbg.info('ready');
	BL.sizeStructure();
	BL.ui.init();
	BL.user.init();
	document.oldTitle = document.title;
});

$(window).resize(function()
{
	BL.dbg.info('resize');
	BL.sizeStructure();
});

$(window).unload(function()
{
	BL.dbg.info('unload');
//	$.cookie('user',$('#user').val(),{ expires: 7});
});

$(window).bind('blur', function(){
	BL.dbg.info('blur');
	BL.away.bIsAway = true;
	BL.away.iSince = new Date().getTime();
});

$(window).bind('focus', function(){
	BL.dbg.info('focus');
	BL.away.bIsAway = false;
	BL.chat.obj.iUnreadMsg = 0;
	BL.flashTitle.reset();
});

$(document).bind('login', function(){
	BL.dbg.info('login');
	BL.ui.closePopin('login');
//	BL.user.
	BL.chat.init();
});
$(document).bind('needLogin', function(){
	BL.dbg.info('needLogin');
	BL.ui.openPopin(BL.ui.obj.oPopinTpl.base, BL.ui.obj.oPopin, {'type':'string','Content':BL.ui.obj.oPopinCtn.login,'context':'login'});
});
