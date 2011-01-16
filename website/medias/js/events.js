/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 26 sept. 2010
 * Time: 03:52:26
 * To change this template use File | Settings | File Templates.
 */

/**
 * lunch action, the very first start
 */
$().ready(function()
{
	BL.dbg.info('ready');
    BL.ui.obj.sCurrentPane = document.location.hash.split('#!/')[1];
    BL.sizeStructure();
    BL.ui.init();
    BL.user.init();
    BL.chat.init();
    document.oldTitle = document.title;
});

/**
 * when window is resize
 */
$(window).resize(function()
{
	BL.dbg.info('resize');
	BL.sizeStructure();
});

/**
 * when quit/refresh application
 */
$(window).unload(function()
{
	BL.dbg.info('unload');
//	$.cookie('user',$('#user').val(),{ expires: 7});
});

/**
 * when going out the window
 */
$(window).bind('blur', function(){
	BL.dbg.info('blur');
	BL.away.bIsAway = true;
	BL.away.iSince = new Date().getTime();
    BL.chat.pauseChat();
});

/**
 * when going back to the window
 */
$(window).bind('focus', function(){
	BL.dbg.info('focus');
	BL.away.bIsAway = false;
    BL.chat.obj.iTimer = BL.chat.obj.iTimer / 10;
	BL.chat.obj.iUnreadMsg = 0;
	BL.flashTitle.reset();
    BL.ui.navigateOption();
});

/**
 * when application validate the login
 */
$(document).bind('login', function(){
	BL.dbg.info('login');
	BL.ui.closePopin('login');
	if(document.location.hash.split('#!/').length == 2){

		if(!BL.ui.navigate(document.location.hash.split('#!/')[1]))
		{

			//do somthing?

		}

	}

});

/**
 * when the application need to login
 */
$(document).bind('needLogin', function(){
	BL.dbg.info('needLogin');
	BL.ui.openPopin(BL.ui.obj.oPopinTpl.base, BL.ui.obj.oPopin, {'type':'string','Content':BL.ui.obj.oPopinCtn.login,'context':'login'});
	$('#loginEmail').focus();
});

/**
 * to navigate into the application
 */
$('a.UInavigation').bind('click', function(e){
	BL.dbg.info('navigation click');
	$(e).stop();
	sHash = e.target.href;

	if(document.location.hash.split('#!/')[1] != sHash.split('!/')[1]){
		BL.ui.navigate(sHash.split('#!/')[1]);
		document.location.hash = sHash.split('#')[1];
	}

	return false;

});

/* logout */
$('#logout').bind('click', function(e){

	BL.dbg.info('logout click');
	$(e).stop();

    BL.user.logout();

	return false;

});

$(document).bind('logout', function(){
    BL.dbg.info('logout reload');
    document.location.reload();
});

$(document).bind('explode', function(){
	BL.dbg.info('needLogin');
	BL.ui.openPopin(BL.ui.obj.oPopinTpl.base, BL.ui.obj.oPopin, {'type':'string','Content':'<p>OSHI WE GONNA DIE!!!</p>','context':'explode'});
	$('#loginEmail').focus();
});