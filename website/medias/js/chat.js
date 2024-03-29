var SVNREVISION = '$Rev$';
/* jquery 1.4.2 based */
/* namespace = BL*/


/*

l'user arrive
* init du chat
** stock les objets HTML
** peuple les derniers chats
* refresh du chat toute les xx ms
* l'user post un message
** stocké en base
** une fois stocké, force du refresh du chat

*/
/**
 * BL namespace
 */

BL.chat = {
	obj:{
		JQoForm:null,
		JQoFormTxtarea:null,
		JQoChats:null,
		JQoChatList:null,
		bCanScroll:true,
		bCanPost:true,
		iLastId:0,
		iRefreshTime:null,
		timeOut:null,
//		onlineTimeOut:null,
		iNbrRefreshWhos:0,
		iUnreadMsg:0,
		oPostedMsg:{'iPos':0,'aMsgs':['']}
	},
	init:function(){
		BL.chat.obj.JQoForm = $('#chatForm');
		BL.chat.obj.JQoChats = $('#chat');
		BL.chat.obj.JQoChatList = $('#chatList');
		BL.chat.obj.JQoFormTxtarea = BL.chat.obj.JQoForm.find('textarea');

		BL.chat.obj.JQoFormTxtarea.focus();

		$('#user').val($.cookie('user'));

		BL.chat.obj.JQoForm.submit(function(e)
		{
			if($('#chatMsg').val().length > 1) {
				//console.info(this);
				e.stopPropagation();
				BL.chat.sendChat($(this));
				$('#chatMsg').val('');
			}
		});

		$('#chatMsg').keydown(function(e)
		{
			if(e.keyCode == 13)
			{
				BL.chat.obj.JQoForm.trigger('submit');
				//console.info(this);
				e.preventDefault();
			}
		});

		BL.chat.obj.JQoChatList.scroll(function()
		{
			if(BL.chat.obj.JQoChatList[0].scrollTop + BL.chat.obj.JQoChatList.height() == BL.chat.obj.JQoChatList[0].scrollHeight)
			{
				BL.chat.obj.bCanScroll = true;
			}
			else
			{
				BL.chat.obj.bCanScroll = false;
			}

		}).click(function(e){
			if($(e.target).hasClass('pseudo'))
			{
				var JQTxt = BL.chat.obj.JQoFormTxtarea;
				//console.info(e.target);
				JQTxt.val(e.target.innerHTML + ' : ' + JQTxt.val()).focus();
				if (JQTxt[0].setSelectionRange)
				{
					JQTxt[0].setSelectionRange(JQTxt[0].textLength, JQTxt[0].textLength)
				}
				else
				{
					var range = JQTxt[0].createTextRange();
					range.collapse(true);
					range.moveEnd('character', JQTxt[0].innerText.length);
					range.moveStart('character', JQTxt[0].innerText.length);
					range.select();
				}

			}
		});
		BL.chat.obj.JQoFormTxtarea.keydown(function(e)
		{
			if(BL.chat.obj.oPostedMsg.aMsgs.length == 0){return}			
			if(e.ctrlKey){
				iPos =BL.chat.obj.oPostedMsg.iPos;
				if(e.keyCode == 40)
				{
					//down
//					console.info(BL.chat.obj.oPostedMsg.iPos, '++');

					BL.chat.obj.oPostedMsg.iPos++;

					if(BL.chat.obj.oPostedMsg.iPos >= BL.chat.obj.oPostedMsg.aMsgs.length)
					{
						BL.chat.obj.oPostedMsg.iPos = 0;
					}

					$(e.target).val(BL.chat.obj.oPostedMsg.aMsgs[BL.chat.obj.oPostedMsg.iPos])

				}
				if(e.keyCode == 38)
				{
					//up
//					console.info(BL.chat.obj.oPostedMsg.iPos, '--');

					BL.chat.obj.oPostedMsg.iPos--;

					if(BL.chat.obj.oPostedMsg.iPos <= -1)
					{
						BL.chat.obj.oPostedMsg.iPos = BL.chat.obj.oPostedMsg.aMsgs.length-1;
					}

					$(e.target).val(BL.chat.obj.oPostedMsg.aMsgs[BL.chat.obj.oPostedMsg.iPos])
				}
			}
		});
		BL.chat.refreshView(true);
//		BL.chat.isOnline();

	},
	/**
	 *
	 * @param oData object
	 */
	sendChat:function(oData){
		if(!BL.chat.obj.bCanPost){return}
		BL.chat.obj.bCanPost = false;
		BL.chat.obj.JQoForm.fadeTo(100,0.5);
//		console.info(oData);
		BL.chat.obj.oPostedMsg.aMsgs.push(BL.chat.obj.JQoFormTxtarea.val());
		$.ajax({
			url:"ws/chat.php",
			type:"POST",
			data:oData.serialize(),
			success:function(){

				BL.chat.refreshView(false);
				BL.chat.obj.bCanPost = true;
				BL.chat.obj.JQoForm.fadeTo(100,1);
			}
		})

	},
	/**
	 *
	 * @param bIsFirst bolean
	 */
	refreshView:function(bIsFirst){
		clearTimeout(BL.chat.obj.timeOut);

		BL.chat.obj.iRefreshTime = new Date();
		var sUrl = (bIsFirst) ? 'ws/chat.php?html=true' : 'ws/chat.php';
		$.ajax({
			url:sUrl,
			cache:false,
			data:{lastid:BL.chat.obj.iLastId},
			success:function(data){
				BL.chat.buildView(data, bIsFirst);
				BL.chat.scrollTo();
				BL.chat.obj.timeOut = setTimeout(function(){BL.chat.refreshView(false)},2000);
				if(BL.chat.obj.iNbrRefreshWhos-- == 0)
				{
					BL.chat.isOnline();
					BL.chat.obj.iNbrRefreshWhos = 5;
				}
	      $('#timerTracker').find('.gaugeTT').animate({width:(new Date() - BL.chat.obj.iRefreshTime) /10 + '%'},100);
			}
		});
	},
	/**
	 *
	 * @param data string
	 * @param bIsHtml bolean
	 */
	buildView:function(data, bIsHtml){
		//first case, write HTML via inner
		//console.info(data.substr(0,10));
		if(bIsHtml){
    	BL.chat.obj.JQoChatList.html(data);
			// on recupere l'id du dernier message
			BL.chat.obj.iLastId = parseInt(BL.chat.obj.JQoChatList.find("div:last")[0].id.split('chat')[1]);

		}
		//otherwise, DOM is injected
		else
		{
			if(data == '' || data == '[' || data.substr(0,3) == '<br'){
				return false;
			}

			var JSONChat = eval(data);
			if(JSONChat.length == 0){return;}
			if(JSONChat[0].newMsg == false){return;}

			var i = 0;
			while(JSONChat.length > i)
			{
				if(document.getElementById('chat' + JSONChat[i].index)){return}
				var oDiv = document.createElement('div');
				oDiv.className = 'padding';
				oDiv.id = 'chat' + JSONChat[i].index;
				var sTpl = '';
				sTpl += '<p class="user">';
				sTpl += '<a class="pseudo">' +JSONChat[i].user +'</a>';
//				sTpl += JSONChat[i].id;
				sTpl += ' <span class="date">à : ' + JSONChat[i].date + '</span></p>';
				var sClassIsQuoted = (JSONChat[i].post.match(document.getElementById('user').value)) ? ' bold':'';
				sTpl += '<p class="post' + sClassIsQuoted + '">' + JSONChat[i].post.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,'<a href="$1" onclick="window.open(this.href);return false;">$1</a>', JSONChat[i].post) + '</p>';
				sTpl += '';
				oDiv.innerHTML = sTpl;
				$(oDiv).insertAfter(BL.chat.obj.JQoChatList.find("div:last"));
				if(sClassIsQuoted && BL.away.bIsAway)
				{
					//flashTitle
					BL.flashTitle.flash();
				}
				i++;
			}
			BL.chat.scrollTo();

			BL.chat.obj.iLastId = JSONChat[JSONChat.length-1].index;
		}


	},
	scrollTo:function(){
		if(BL.chat.obj.bCanScroll)
		{
	   	BL.chat.obj.JQoChatList.animate({scrollTop:BL.chat.obj.JQoChatList[0].scrollHeight},500);
		}

	},
	isOnline:function(){
//		clearTimeout(BL.chat.obj.onlineTimeOut);
		$.ajax({
			url:"ws/whosonline.php",
			type:"POST",
			data:BL.chat.obj.JQoForm.serialize(),
			success:function(data){

				$('#frienBar').find('.friendList').html(data);
//				BL.chat.obj.onlineTimeOut = setTimeout(BL.chat.isOnline,15000);

			}
		});
		
	}

};
BL.blinkTitle = null;
BL.clearClearBlinkTitle = null;
BL.flashTitle = {
	reset:function(){
		clearInterval(BL.blinkTitle);
		clearTimeout(BL.clearClearBlinkTitle);
		document.title = document.oldTitle;
	},
	flash:function(){
		BL.flashTitle.reset();
		//TODO: des bugs aléatoire d'affichage à corriger
		// first set to see immediatly the modified title
		document.title = '(' + ++BL.chat.obj.iUnreadMsg + ') ' + document.oldTitle;

		// flashing the title
		BL.blinkTitle = setInterval(function()
		{
//			console.info('test');
			if(document.title == document.oldTitle)
			{
				document.title = '(' + BL.chat.obj.iUnreadMsg + ') ' + document.oldTitle;
			}
			else
			{
				document.title = document.oldTitle;
			}
		},1000);

		BL.clearClearBlinkTitle = setTimeout(function()
		{
			clearInterval(BL.blinkTitle);
		},7000)
	}
};

BL.away = {
	bIsAway:false,
	iSince:0

};
