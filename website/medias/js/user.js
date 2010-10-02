/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 19 sept. 2010
 * Time: 22:32:45
 * To change this template use File | Settings | File Templates.
 */
BL.user = {
	init:function()
	{
		BL.user.sendCookies();
		$('#create').submit(function(e)
		{
			BL.user.create($(this).serialize());
			e.stopPropagation();
			return false;
		});
		$('#login').submit(function(e)
		{
			BL.user.login($(this).serialize());
			e.stopPropagation();
			return false;
		});
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
			 //BL.user.login()
				console.info(response);
				$(document).trigger('login');

			},
			error:function(xhr, ajaxOptions, thrownError){
				console.error(xhr, ajaxOptions, thrownError);
			}
		});
		
	},
	sendCookies:function()
	{
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
				console.info(response[0]);
				if(response[0].msg)
				{
					console.info('oui');
					$(document).trigger('login');
				}
				else{
					console.info('non');
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

	},
	/**
	 *
	 * @param sEmail
	 * @param sPseudo
	 * @param sMdp
	 * @param sAvatar
	 * @param sDesc
	 */
	modify:function(sEmail, sPseudo, sMdp, sAvatar, sDesc)
	{

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
