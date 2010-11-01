/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 19 sept. 2010
 * Time: 22:32:45
 * To change this template use File | Settings | File Templates.
 */
BL.user = {
	/**
	 * user info
	 * contains data: {pseudo, email, avatar, uid}
	 */
	info:{'pseudo':'','email':'','avatar':'','uid':''},
	/**
	 * obj temporary
	 *
	 * oCreate    : Jquery DOM object
	 * oModify    : Jquery DOM object
	 * oLogout    : Jquery DOM object
	 * oModifyPsw : Jquery DOM object
	 * oLogin     : Jquery DOM object
	 */
	obj:{
		oCreate:null,
		oModify:null,
		oLogout:null,
		oModifyPsw:null,
		oLogin:null
	},
	/**
	 *
	 */
	init:function()
	{
		BL.dbg.info('start:: init');

		BL.user.obj.oCreate = $('#create');
		BL.user.obj.oCreate.submit(function(e)
		{
			BL.user.create($(this).serialize());
			e.stopPropagation();
			return false;
		});

		BL.user.obj.oCreate = $('#modify');
		BL.user.obj.oCreate.submit(function(e)
		{
			BL.user.modify($(this).serialize());
			e.stopPropagation();
			return false;
		});

		BL.user.obj.oLogin = $('#login');
		BL.user.obj.oLogin.live('submit',function(e)
		{
			BL.user.login($(this).serialize());
			e.stopPropagation();
			return false;
		});
		$(document).trigger('inited');
		BL.user.sendCookies();
	},
	/**
	 *
	 * @param sParam  a string with all param containing these:
	 ** sEmail
	 ** sPseudo
	 ** sMdp
	 ** sAvatar
	 ** sDesc
	 */
	create:function(sParam)
	{
		
		BL.dbg.info('start:: user create');
		var sUrl = 'ws/user.php';
		var sData = sParam;
		console.info(sData);

		$.ajax({
			url:sUrl,
			type:'POST',
			cache:false,
			data:sData,
			success:function(sParam){
//			 BL.user.login()
			},
			error:function(sData){
				console.error(sData);
			}
		});

	},
	/**
	 *
	 * @param sParam  a string with all param containing these:
	 ** sEmail
	 ** sMdp
	 */
	login:function(sParam)
	{
		BL.dbg.info('start:: user login');
		var sUrl = 'ws/user.php';
		var sData = sParam;

		$.ajax({
			url:sUrl,
			type:'POST',
			cache:false,
			data:sData,
			dataType:'json',
			success:function(response){
				BL.user.info = response[0].user;
				$(document).trigger('login');

			},
			error:function(xhr, ajaxOptions, thrownError){
				console.error(xhr, ajaxOptions, thrownError);
			}
		});
		
	},

	/**
	 * send cookies data to server to make an autologin or send popin
	 */
	sendCookies:function()
	{
		BL.dbg.info('start:: user sendCookies');
		
		var sUrl = 'ws/user.php';
		var sData = {'ctn':'cks'};

		$.ajax({
			url:sUrl,
			type:'POST',
			cache:false,
			data:sData,
			dataType:'json',
			success:function(response){
			 //BL.user.login()
				if(response[0].msg)
				{
					BL.user.info = response[0].user;
					$(document).trigger('login');
				}
				else{
					$(document).trigger('needLogin');
				}

			},
			error:function(xhr, ajaxOptions, thrownError){
				console.error(xhr, ajaxOptions, thrownError);
			}
		});

	},
	/**
	 *
	 * @param iId
	 */
	logout:function(iId)
	{
        BL.dbg.info('start:: user logout');

        var sUrl = 'ws/user.php';
        var sData = {'ctn':'lgt'};

        $.ajax({
            url:sUrl,
            type:'POST',
            cache:false,
            data:sData,
            dataType:'json',
            success:function(response){
             //BL.user.login()
                BL.dbg.info(response);
                if(response[0].msg)
                {
                    BL.dbg.info("ok");
                    $(document).trigger('logout');
                }
                else{
                    BL.dbg.info("nok");
                    $(document).trigger('explode');
                }

            },
            error:function(xhr, ajaxOptions, thrownError){
                console.error(xhr, ajaxOptions, thrownError);
            }
        });

	},
	/**
	 *
	 * @param sParam
	 * 
	 */
	modify:function(sParam)
	{

		BL.dbg.info('start:: user modify');
		var sUrl = 'ws/user.php';
		var sData = sParam;
		console.info(sData);

		$.ajax({
			url:sUrl,
			type:'POST',
			cache:false,
			data:sData,
			dataType:'json',
			success:function(response){

				BL.dbg.info(response);
				if(response[0].msg)
				{

					BL.user.info = response[0].user;
					$(document).trigger('userModifier');

				}

			},

			error:function(sData){

				$(document).trigger('userModifierError');
				console.error(sData);

			}
		});

	},
	setUserInfoToField:function(){
		BL.user.obj.oCreate.find('input[name=email]').val(BL.user.info.email);
		BL.user.obj.oCreate.find('input[name=pseudo]').val(BL.user.info.pseudo);
//		BL.user.obj.oCreate.find('input[name=desc]').val(BL.user.info.);
	},
	/**
	 *
	 * @param iId integer
	 * @param sEmail
	 * @param sMdp
	 * @param sNewMdp
	 */
	modifyPsw:function(iId, sEmail, sMdp, sNewMdp)
	{

	}
};
