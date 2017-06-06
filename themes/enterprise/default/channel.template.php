
<div id="dcWrap">
 <div id="dcHead">
 <div id="head">
  <div class="logo"><a href="./"><img src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/images/mlogo.gif" alt="logo"></a></div>
  <div class="nav">
<?php
     $id = SessionManager::getInstance()->getLoginId();
     $enterprise = MysqlInterface::getEnterpriseById($id);

?>
   <ul>
     <li class="noRight"><a href="#"> 企业编号：<?php echo $enterprise['id'];?></a></li>
     <li class="noRight"><a href="#"> 企业名称：<?php echo $enterprise['name'];?> </a> </li>
   </ul>
   <ul class="navRight">
    <li class="noLeft"><a href="#">可用永久卡：<?php echo $enterprise['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">可用年卡：<?php echo $enterprise['availableCards'];?></a></li>
    <li class="M noLeft"><a href="JavaScript:void(0);">您好，<?php echo SessionManager::getInstance()->getLoginName();?></a>
     <div class="drop mUser">
            <a href="?page=admins&sid=1">编辑我的个人资料</a>
     </div>
    </li>
    <li class="noRight"><a href="./?page=logout">退出</a></li>
   </ul>
  </div>
 </div>
</div>
<!-- dcHead 结束 --> <div id="dcLeft"><div id="menu">

  <ul>
  <li><a href="?page=user&sid=1"><i class="user"></i><em>用户管理</em></a></li>
  <li><a href="?page=server&sid=1"><i class="mobile"></i><em>频道管理</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="article"></i><em>监控管理</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>交易记录</em></a></li>
 </ul>

</div></div>
 <div id="dcMain">
   <!-- 当前位置 -->
<div id="urHere">管理中心<b>></b><strong><?php if($_GET['action']=='add'){?>频道添加<?php }elseif($_GET['action']=='edit'){?>频道修改<?php }?></strong> </div>  

 <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        
 <?php
 	if (isset($_GET['action']) && $_GET['action']=='add') {
 ?> 
 <h3>频道添加</h3>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="80" align="right">频道名称</td>
       <td>
        <input type="text" id="channelName" name="cat_name" value="" size="40" class="inpMain" />
       </td>
      </tr>

      <tr>
       <td></td>
       <td>
		
        <input name="button" class="btn" type="submit" value="提交" onclick="jq_server_channel_add();return false;" />
       </td>
      </tr>
     </table>

		  
<?php
}

?>
 <?php
 	if (isset($_GET['action']) && $_GET['action']=='edit') {
                $id = SessionManager::getInstance()->getLoginId();
		$cid =intval($_GET['cid']);
		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']));
					$ChannelName = $server->getChannel(intval($id), $cid);
					//$Channelid = $server->getChannelState($cid);
					//var_dump($server);
					
	
	
 ?> 
 
 <h3>频道修改</h3>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="80" align="right">频道名称</td>
       <td>
        <input type="text" id='channelName' name="cat_name" value="<?php echo $ChannelName; ?>" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
		<input type="hidden" id="cid" name="cid" value="<?php echo $_GET['cid'] ?>" size="40" class="cid" />
        <input name="button" class="btn" type="submit" value="更新" onclick="jq_server_channel_save();return false;" />
       </td>
      </tr>
     </table>

		  
<?php
}

?>
<div id="jq_information"></div>
 </div>

<?php
	if (!isset($_GET['sid']) || empty($_GET['sid'])) {
		$servers = ServerInterface::getInstance()->getServers();
?>
		<h1>Select a server</h1>
		<ul>
<?php
			foreach ($servers AS $server) {
				if (PermissionManager::getInstance()->isAdminOfServer($server->id())) {
?>
					<li><a href="?page=server&amp;sid=<?php echo $server->id(); ?>"><?php echo $server->id().': '.SettingsManager::getInstance()->getServerName($server->id()); ?></a></li>
<?php
				}
			}
?>
		</ul>
<?php
	} else {
		$_GET['sid'] = intval($_GET['sid']);
		//if (!PermissionManager::getInstance()->isAdminOfServer($_GET['sid'])) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
		$server = ServerInterface::getInstance()->getServer($_GET['sid']);
?>




	<script type="text/javascript">
		/*<![CDATA[*/
			function randomString(length)
			{
				var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz§$%&/()=?!{[]}";
				var str = '';
				for (i=0; i < length; i++) {
					var r = Math.floor(Math.random() * chars.length);
					str += chars.substring(r, r+1);
				}
				return str;
			}
			function jq_server_setSuperuserPassword(sid)
			{
				$('#li_server_superuserpassword > .ajax_info').html(imgAjaxLoading);
				var pw = randomString(6);
				$.post('./?ajax=server_setSuperuserPassword',
						{ 'sid': 1, 'pw': pw },
						function (data) {
							if (data=='') {
								$('#li_server_superuserpassword > .ajax_info').html('<div>Password set to: '+pw+'</div>');
							} else {
								$('#li_server_superuserpassword > .ajax_info').html(data);
							}
						}
					);
			}
		
			function jq_server_registration_remove(uid)
			{
				$.post(
							"./?ajax=server_regstration_remove&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid },
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
								}
								jq_server_getRegistrations(<?php echo $_GET['sid']; ?>);
							}
				);
			}
			
		function jq_server_channel_remove(aid)
			{
				$.post(
							"./?ajax=server_channel_remove?entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'aid': aid },
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
								}
							jq_server_showTree(<?php echo $_GET['sid']; ?>);	
							}
				);
			}
			
	function jq_server_channel_add()
		{
			var name = $('#channelName').val();

			$.post("./?ajax=server_channel_add&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
					{ 'name': name,'sid': <?php echo $_GET['sid']; ?> },
					function(data)
					{
						if (data.length>0) {
                                                        window.wxc.xcConfirm("添加成功", window.wxc.xcConfirm.typeEnum.success);
							location.href="./?page=server&sid=1";
						} else {
							
						}
					
					}
				);

		}
	function jq_server_channel_save()
		{
			var cid = $('#cid').val();
			var name = $('#channelName').val();
			$.post("./?ajax=server_channel_save&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
					{ 'cid': cid,'name':name,'sid': <?php echo $_GET['sid']; ?> },
					function(data)
					{
						if (data.length>0) {
					               window.wxc.xcConfirm("修改成功", window.wxc.xcConfirm.typeEnum.success);	
						       url="./?page=server&sid=1";
						       times=5;
						       loadUrl(url,times);

						} else {
														
						}
						
					}
				);

		}		
			
function loadUrl(url,times){
	
	for(var i=times;i>=0;i--) { 
   window.setTimeout(showNm(i), (times-i) * 1000); 
} 
	
	
}

function showNm(num){
	$("jq_information").html("将在"+num+"秒跳转主页");
if(num==0){
	
	location.href=url;
}

	
}

			function jq_server_user_genNewPw(serverId, userId)
			{
				var newPw = randomString(6);
				$.post(
							"./?ajax=server_regstration_genpw",
							{ 'serverId': serverId, 'userId': userId, 'newPw': newPw },
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
								} else {
									alert('Password set to: ' + newPw);
								}
								jq_server_getRegistrations(serverId);
							}
				);
			}
			function jq_channel_add(uid, newVal)
			{
				
				var serverId = <?php echo $_GET['sid']; ?>;
				$.post("./?ajax=server_user_updateUsername",
						{ 'sid': serverId, 'uid': uid, 'newValue': newVal },
						function (data) {
							if (data.length>0) { alert('failed: '+data); }
							jq_server_getRegistrations(serverId);
						}
					);
			}
			function jq_user_updateEmail(uid, newVal)
			{
				$('#user_name_'+uid).append(imgAjaxLoading);
				$.post("./?ajax=server_user_updateEmail",
						{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid, 'newValue': newVal },
						function (data) {
							if (data.length>0) { alert('failed: '+data); }
							jq_server_getRegistrations();
						}
					);
			}
		
			function jq_server_showTree(sid)
			{
				$.post("./?ajax=show_tree",
						{ 'sid': sid },
						function(data){
							$('#jq_information').show().html(data);
						}
					);
			}
		
			//$('#jq_information').show().html($(parent).id());
		/*]]>*/
	</script>
<?php } ?>
