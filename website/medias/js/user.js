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
		$('#create').submit(function(e)
		{
			BL.user.create($(this).serialize());
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
		var sData = sParam + '&ctn=crt';
		console.info(sData);

		$.ajax({
			url:sUrl,
			type:'POST',
			cache:false,
			data:sData,
			success:function(sParam){
			 BL.user.login()
			},
			error:function(sData){
				console.error(sData);
			}
		});

	},
	/**
	 *
	 * @param sEmail
	 * @param sMdp
	 */
	login:function(sEmail, sMdp)
	{
		
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
