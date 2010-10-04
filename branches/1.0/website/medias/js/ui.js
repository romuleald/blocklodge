var SVNREVISION = '$Rev$';
/* jquery 1.4.2 based */
/* namespace = BL*/


/*


*/
/**
 * ui namespace
 */

BL.ui = {
	obj:{
		oPopin:null,
		oPopinMask:null,
		oPopinTpl:{
			'base':'<div class="popin"><div class="head"><a class="close">x</a></div><div class="content">$$$$</div></div>'
		},
		oPopinCtn:{
			'login':'<div><form id="login"><p><label for="loginEmail">email</label><input type="text" id="loginEmail" name="email" /></p><p><label for="loginPsw">mdp</label><input id="loginPsw" type="password" name="mdp" /></p><p><input id="loginSend" type="submit" value="send" /></p><input type="hidden" name="ctn" value="lgn" /></form></div>'
		},
		JQoFormTxtarea:null
	},
	init:function(){
		BL.ui.obj.oPopin = $('#popin');	
		BL.ui.obj.oPopinMask = $('#mask');
	},
	/**
	 *
	 * @param oScrolled
	 * @param iScrolling
	 * @param iLatency can be null
	 */
	scroll:function(oScrolled, iScrolling, iLatency){

		if(typeof iLatency == 'undefined'){iLatency = 500;}

		oScrolled.animate({scrollTop:iScrolling},iLatency);

	},
	/**
	 * parse history of the textarea oTextBox
	 * @param event
	 * @param oTextBox
	 */
	historyTextBox:function(event, oTextBox){

	},
	/**
	 * quote an user in the textarea oTextBox
	 * @param sUser
	 * @param oTextGBox
	 */
	quoteUser:function(sUser, oTextGBox){

	},
	/**
	 * open a popin
	 * @param {String} sLayout the $$$$ in the string is the content to replace
	 * @param oPopin DOM object
	 * @param {object} oOption = {'type':'ajax','Content':'$url','context':'context'} || {'type':'string','Content':'$content','context':'context'}
	 */
	openPopin:function(sLayout, oPopin, oOption){

		BL.ui.obj.oPopin.attr('class','login').animate({top:'20%'},500);
		BL.ui.obj.oPopinMask.show(0).animate({opacity:.9},500);
		if(oOption.type == 'ajax')
		{
			$.ajax({
				url:oOption.Content,
				type:'GET',
				cache:false,
				data:'JSON',
				success:function(data){
					oPopin.html(sLayout.replace('$$$$', data));
				}
			});
		}
		else if(oOption.type == 'string'){
			oPopin.html(sLayout.replace('$$$$', oOption.Content));
		}

	},
	/**
	 * need a valid className popin to identify the good popin to close, can be empty to close all popin
	 * @param {String} sPopin
	 */
	closePopin:function(sPopin){
		if(typeof sPopin == 'string'){
			if(sPopin != BL.ui.obj.oPopin.attr('class')){return false;}	
		}
		BL.ui.obj.oPopin.animate({top:'-50%'},500);
		BL.ui.obj.oPopinMask.animate({opacity:0},500).hide(0);
	},
	/**
	 * ? popin help
	 * j, k next/prev message
	 * . focus on textarea
	 * 
	 * @param event
	 */
	shortCut:function(event){

	}
};
