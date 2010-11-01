var SVNREVISION = '$Revision$';
/* jquery 1.4.2 based */

/* nameSpace BL */
var BL = {};

BL.sizeStructure = function()
{
	var iHeight = $(document.body).height() - document.getElementById('top').clientHeight - 20;
	//console.info(iHeight);
	$('#forum,#core,#frienBar').height(iHeight);
	$('#chatList').height(iHeight - 200);
};


BL.dbg = {
	enabled:false,
	log:function(){
		if(BL.dbg.enabled){
			console.log(arguments)
		}
	},
	info:function(){
		if(BL.dbg.enabled){
			console.info(arguments)
		}
	},
	dir:function(){
		if(BL.dbg.enabled){
			console.dir(arguments)
		}
	}
};
if(document.location.host == '1.bl'){BL.dbg.enabled = true;}