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
		var sUrl = (bIsFirst) ? 'chat.php?html=true' : 'chat.php?html=true';
		$.ajax({
			url:sUrl,
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
		}
		else
		{
			BL.chat.obj.JQoChatList.html(data);
			//console.info(eval(data));
		}


	},
	scrollTo:function(){
		if(BL.chat.obj.bCanScroll)
		{
	   	BL.chat.obj.JQoChatList.find("div:last")[0].scrollIntoView();
		}

	}

};