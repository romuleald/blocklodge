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

BL.chat = {
	obj:{
		JQoForm:null,
		JQoChats:null,
		JQoChatList:null,
		bCanScroll:true,
		bCanPost:true,
		iLastId:0,
		timeOut:null
	},
	init:function(){
		BL.chat.obj.JQoForm = $('#chatForm');
		BL.chat.obj.JQoChats = $('#chat');
		BL.chat.obj.JQoChatList = $('#chatList');

		$('#chatForm').submit(function(e)
		{
			if($('#chatMsg')[0].textLength > 1) {
				//console.info(this);
				e.stopPropagation();
				BL.chat.sendChat($(this).serialize())
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

		});
		BL.chat.refreshView(true);

	},
	sendChat:function(sDataToSent){
		//console.info(sDataToSent);
		$.ajax({
			url:"chat.php",
			type:"POST",
			data:sDataToSent,
			success:function(){
				//console.info('data', data);
				$('#chatMsg')[0].value = '';
				BL.chat.refreshView(false);
			}
		})

	},
	refreshView:function(bIsFirst){
		clearTimeout(BL.chat.obj.timeOut);
		//console.info('refresh');
		var sUrl = (bIsFirst) ? 'chat.php?html=true' : 'chat.php';
		$.ajax({
			url:sUrl,
			data:{lastid:BL.chat.obj.iLastId},
			success:function(data){
				BL.chat.buildView(data, bIsFirst);
				BL.chat.scrollTo();
				BL.chat.obj.timeOut = setTimeout(function(){BL.chat.refreshView(false)},1500);
			}
		});

	},
	buildView:function(data, bIsHtml){
		//actually html is build by php, after, php will send json and html will be build here
		if(bIsHtml){
    	BL.chat.obj.JQoChatList.html(data);
			// on recupere l'id du dernier message
			BL.chat.obj.iLastId = parseInt(BL.chat.obj.JQoChatList.find("div:last")[0].id.split('chat')[1]);

		}
		else
		{
//			BL.chat.obj.JQoChatList.html(data);
			var JSONChat = eval(data);
			if(JSONChat.length == 0){return;}
//			console.info(JSONChat);
			var i = 0;
			while(JSONChat.length > i)
			{
				var oDiv = document.createElement('div');
				oDiv.className = 'padding';
				oDiv.id = 'chat' + JSONChat[i].index;
				var sTpl = '';
				sTpl += '<p class="post">';
				sTpl += JSONChat[i].user;
				sTpl += JSONChat[i].id;
				sTpl += '</p>';
				sTpl += '<p class="post">' + JSONChat[i].post + '</p>';
				sTpl += '<p class="date">à : ' + JSONChat[i].date + '</p>';
				oDiv.innerHTML = sTpl;
				$(oDiv).insertAfter(BL.chat.obj.JQoChatList.find("div:last"));
				i++;
			}

			//console.info($(sTpl));
			BL.chat.obj.iLastId = JSONChat[JSONChat.length-1].index;
		}


	},
	scrollTo:function(){
		if(BL.chat.obj.bCanScroll)
		{
	   	BL.chat.obj.JQoChatList.find("div:last")[0].scrollIntoView();
		}

	}

};