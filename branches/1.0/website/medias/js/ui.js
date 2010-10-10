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
		JQoFormTxtarea:null,
		aValideUri:['chat','forum','message','profil']
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
	 *
	 * @param {String} sAction
	 */
	sizePan:function(sAction){
		if(sAction == 'showforum')
		{
			$('#forum').animate({width:'60%'},500);
			$('#core').animate({width:'25%'},500);
		}
		else if(sAction = 'showCore')
		{
			$('#forum').animate({width:'25%'},500);
			$('#core').animate({width:'60%'},500);
		}
	},
	oCurrentPan:false,
	/**
	 *
	 * @param {Object} oPan jQuery unique DOM Object
	 */
	showCurrentPan: function(oPan){
		if(BL.ui.oCurrentPan){
			BL.ui.oCurrentPan.css({opacity:.6}).animate({opacity:'0'},200, function(){
				$(this).css({left:'-100%',opacity:1});
			});
		}
		oPan.css({left:'-60%'}).animate({left:'0%'},200);
		BL.ui.oCurrentPan = oPan;
	},
	/**
	 *
	 * @param {String} sPane
	 */
	navigate: function(sPane){

		BL.dbg.info('navigate', sPane);

		if(BL.ui.isValidePath(document.location.hash.split('#!/')[1])){


			/*

				switch between panel, if go on chat, active/reumse it, if leave it, pause it
				etc etc for others panes

			*/

			BL.ui.showCurrentPan($('#' + sPane));
			return true;

		}

		return false;

	},
	/**
	 * return true or false if the path asked exist
	 * @param {String} sPath
	 */
	isValidePath:function(sPath){

		for(var i = 0;BL.ui.obj.aValideUri.length;i++){
			if(BL.ui.obj.aValideUri[i] == sPath){

				return true;

			}

			return false;

		}
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
