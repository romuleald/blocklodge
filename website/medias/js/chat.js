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
		$('#chatForm').submit(function(e){
			e.stopPropagation();
			//console.info($(this).find('textarea')[0].value)
			BL.chat.sendChat($(this).serialize())
		});
		BL.chat.refreshView();
	},
	sendChat:function(sDataToSent){
		console.info(sDataToSent);
		$.ajax({
			url:"chat.php",
			type:"POST",
			data:sDataToSent,
			success:function(data){
				console.info('data', data)
				BL.chat.refreshView();
			}
		})
	},
	refreshView:function(){
		clearTimeout(BL.chat.obj.timeOut)
		$.ajax({
			url:"chat.php",
			success:function(data){
				console.info('data', data)
				BL.chat.buildView(data)
			}
		});
		BL.chat.obj.timeOut = setTimeout(BL.chat.refreshView,1000);
	},
	buildView:function(data){
		//actually html is build by php, after, php will send json and html will be build here
		BL.chat.obj.JQoChatList.html(data);
	}

}