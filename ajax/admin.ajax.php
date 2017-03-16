<?php
/**
 * Ajax functionality
 * @author Kissaki
 */

require_once dirname(__FILE__).'/ajax.ajax.php';

/**
 * ajax functionality, functions for the admin section
 * @author Kissaki
 */
class Ajax_Admin extends Ajax
{
	public static function getPage()
	{
		TemplateManager::parseTemplate($_GET['page']);
	}

	public static function db_admins_groups_get()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		$groups = DBManager::getInstance()->getAdminGroups();
		echo json_encode($groups);
	}

	public static function db_adminGroupHeads_get()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		$groups = DBManager::getInstance()->getAdminGroupHeads();
		echo json_encode($groups);
	}

	public static function db_admingroups_echo()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;
?>
		<table>
			<thead><tr><th>ID</th><th>name</th><th>permissions</th><th>servers</th><th>actions</th></tr></thead>
			<tbody>
<?php
				$groups = DBManager::getInstance()->getAdminGroups();
				foreach ($groups AS $group) {
					echo '<tr>
						<td>' . $group['id'] . '</td>
						<td>' . $group['name'] . '</td>
						<td style="font-size:0.6em;">';

					// create permissions string
					$tmp = '';
					foreach ($group['perms'] AS $key=>$val) {
						if ($key != 'groupID' && $val) {
							$tmp .= ', ' . $key;
						}
					}
					// strip leading comma
					if (!empty($tmp)) {
						$tmp = substr($tmp, 2);
					}
					echo $tmp;

					echo '</td>';

					// admin on servers
					echo '<td>';
					$tmp = '';
					foreach ($group['adminOnServers'] AS $srv) {
						$tmp .= $srv.', ';
					}
					echo substr($tmp, 0, strlen($tmp)-2);
					echo '</td>';

					echo '<td>';
					echo 	'<a class="jqlink" onclick="jq_admingroup_perms_edit_display(' . $group['id'] . ')">edit perms</a>, ';
					echo 	'<a class="jqlink" onclick="jq_admingroup_server_assoc_edit_display(' . $group['id'] . ')">edit servers</a>, ';
					echo 	'<a class="jqlink" onclick="jq_admingroup_remove(' . $group['id'] . ')">delete</a>';
					echo '</td>';
					echo '</tr>';
				}
?>
			</tbody>
		</table>
<?php
	}

	public static function db_adminGroup_add()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		DBManager::getInstance()->addAdminGroup($_POST['name']);
		MessageManager::echoAll();
	}

	public static function db_adminGroup_remove()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		DBManager::getInstance()->removeAdminGroup(intval($_POST['id']));
		MessageManager::echoAll();
	}

	public static function db_adminGroup_perms_edit_display()
	{
		// TODO server specific perms
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		// exit on missing params
		if (!isset($_POST['groupID']))
			exit();

		// make sure only ints are passed
		$_POST['groupID'] = intval($_POST['groupID']);
		$_POST['serverID'] = isset($_POST['serverID'])?intval($_POST['serverID']):null;
		$group = DBManager::getInstance()->getAdminGroup($_POST['groupID']);

		// output
		echo '<ul class="form_group_permissions">';
		foreach ($group['perms'] AS $key=>$val) {
			if ($key != 'groupID' && $key != 'serverID') {
				echo sprintf('<li><input type="checkbox" name="%s"%s onclick="jq_admingroup_perm_update(%d, \'%s\', %s);"/> %s</li>',
						$key, $val==true?' checked="checked"':'', $_POST['groupID'], $key, "$('input[name=".$key."]').attr('checked')", $key
					);
			}
		}
		echo '</ul>';
	}

	public static function db_adminGroup_perm_update()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins()) {
			MessageManager::addError('Insufficient privileges.');
		} else {
			DBManager::getInstance()->updateAdminGroupPermission(intval($_POST['gid']), $_POST['perm'], ($_POST['newval']=='true')?true:false);
		}
		MessageManager::echoAll();
	}

	public static function db_adminGroup_perms_edit()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		// TODO: [security] perms should only hold the correct keys and boolean vals
		$group = DBManager::getInstance()->updateAdminGroupPermissions(intval($_POST['gid']), $_POST['perms']);
		MessageManager::echoAll();
	}

	public static function db_adminGroups_makeAdminOnServer()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins()) {
			return;
		}

		$groupID = intval($_POST['groupID']);
		$serverID = intval($_POST['serverID']);

		DBManager::getInstance()->makeAdminGroupAdminOfServer($groupID, $serverID);
	}
	public static function db_adminGroups_revokeAdminOnServer()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins()) {
			return;
		}

		$groupID = intval($_POST['groupID']);
		$serverID = intval($_POST['serverID']);

		DBManager::getInstance()->removeAdminGroupAsAdminOfServer($groupID, $serverID);
	}
	public static function db_adminGroup_servers_edit_display()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		// exit on missing params
		if (!isset($_POST['groupID']))
			exit();

		// make sure only ints are passed
		$_POST['groupID'] = intval($_POST['groupID']);
		$group = DBManager::getInstance()->getAdminGroup($_POST['groupID']);
		$servers = ServerInterface::getInstance()->getServers();

		// output
		echo '<ul class="form_group_servers">';
		foreach ($servers AS $srv) {
			echo sprintf('<li><input type="checkbox" name="%s"%s onclick="jq_adminGroup_server_update(%d, %d, %s);"/> %s</li>',
					'srv'.$srv->id(), in_array($srv->id(), $group['adminOnServers'])?' checked="checked"':'', $_POST['groupID'], $srv->id(), "$('input[name=srv".$srv->id()."]').attr('checked')", SettingsManager::getInstance()->getServerName($srv->id())
				);
		}
		echo '</ul>';
	}

	public static function db_admins_echo()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		echo '<table class="list_admins"><thead><tr class="head"><th>Username</th><th>global Admin</th><th>Groups</th><th>Actions</th></tr></thead>';
		echo '<tbody>';
		$admins = DBManager::getInstance()->getAdmins();
		foreach ($admins AS $admin) {
			$groups = DBManager::getInstance()->getAdminGroupsByAdminID($admin['id']);

			echo '<tr id="admin_list_item_'.$admin['id'].'" class="list_admins_item">';
			echo 	'<td>'.$admin['name'].'</td>';
			echo 	'<td>' . ($admin['isGlobalAdmin'] ? 'yes' : 'no') . '</td>';
			echo 	'<td>';

			echo 		'<ul class="list_groups">';
			foreach ($groups AS $group) {
				echo 		'<li>' . $group['name'] . '</li>';
			}
			echo 		'</ul>';

			echo 	'</td>';
			echo 	'<td>';
			echo 		'<ul>';
			// TODO: I18N
			if (empty($groups))
				echo 			'<li><a title="add" class="jqlink" onclick="jq_admin_addToGroup_display(' . $admin['id'] . ');">addToGroup</a></li>';
			else
				echo 			'<li><a title="add" class="jqlink" onclick="jq_admin_removeFromGroups(' . $admin['id'] . ');">removeFromGroups</a></li>';
			// TODO: I18N
			// if this is the account you're currently logged in as ask explicitly
			if (SessionManager::getInstance()->getAdminID() == $admin['id']) {
				echo '<li><a class="info" title="You can not remove your own account. Instead, use another super-admin account to remove it." style="font-style:strikethrough;"><s>delete</s></a></li>';
			} else {
				echo '<li><a class="jqlink" onclick="jq_admin_remove('.$admin['id'].');">delete</a></li>';
			}
			echo 		'</ul>';
			echo 	'</td>';
			echo '</tr>';
		}
		echo 	'</tbody>';
		echo '</table>';
	}

	public static function db_admin_update_name()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;
		$name=strip_tags($_POST['uname']);
		$pw =strip_tags($_POST['upwd']);
		//echo $name;die;
		DBManager::getInstance()->updateAdminName($name,$pw);
		
	}
	public function server_search(){
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
		return ;	
		$serverId =$_GET['sid'];
		$kw =addslashes($_POST['kw']);
		//echo $kw;
		$sle =intval($_POST['sle']);
		if($sle==2){
			
			$sle==-1;
		}
		
		if($sle==1){
			$sle=1;
			
		}
		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
		$users = $server->getRegisteredUsers();
		unset($users[0]);
		
		$data =array();
	foreach($users as $userId=>$user){
			$user = ServerInterface::getInstance()->getServerRegistration($serverId, $userId);
			//var_dump($user);
			$cid =$user->getCurrentChanId();
		if($sle==2){
			
			if($cid=='-1' && stripos($user->getUserId(),$kw)!==false || stripos($user->getName(),$kw)!==false || stripos($user->getEmail(),$kw)!==false){
				
			$data[]=array($user->getUserId(),$user->getName(),$user->getCurrentChanId(),$user->getEmail());	
			}
			
			
			
		}
			
		if($sle==0){
			if(stripos($user->getUserId(),$kw)!==false || stripos($user->getName(),$kw)!==false || stripos($user->getEmail(),$kw)!==false){
				
			$data[]=array($user->getUserId(),$user->getName(),$user->getCurrentChanId(),$user->getEmail());	
			}
			
			
			
		}		
		
		if($sle==1 && $cid>0){
			if(stripos($user->getUserId(),$kw)!==false || stripos($user->getName(),$kw)!==false || stripos($user->getEmail(),$kw)!==false){
				
			$data[]=array($user->getUserId(),$user->getName(),$user->getCurrentChanId(),$user->getEmail());	
			}
			
			
			
		}
			
			
			
	}
	
	
	?>
	<script>
	var total=<?php echo count($data);?>

	</script>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="150">用户名</th>
	  <th width="150">昵称</th>
      <th width="150" align="center">当前频道</th>

      <th width="80" align="center">操作</th>
     </tr>	
	<?php

		if(!empty($data)){
	foreach($data as $k=>$user){
		
?>
	
<tr>
      <td align="center"><?php echo $user[0];?></td>
      <td><?php echo $user[1];?></td>
      <td align="center"><?php echo $user[3];?></td>
	  <td align="center">
		<?php
		 
	  if(intval($user[2])<0){
		  
		  echo "离线";
	  }elseif(intval($user[2])==0){
		  echo "守候频道";
		  
	  }else{
		 
		  echo $user[2];
	  }
		?>
	  </td>

      <td align="center">
<a href="#" onclick="jq_server_reset_user_password(<?php echo $serverId;?>,<?php echo $userId; ?>);">重置密码</a> | <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_server_registration_remove(<?php echo $userId; ?>);}">删除</a>
	 </td>
     </tr>

<?php


	}
	
		}
		?>
		</table>
		<?php
		
	}
	
	
	public function server_change(){
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
		return ;
	$serverId =$_GET['sid'];
	$vid =intval($_POST['v']);//0 表示全部，1 表示在线，2 表示离线
	
	
		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
		$users = $server->getRegisteredUsers();
		
		unset($users[0]);
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="150">用户名</th>
	  <th width="150">昵称</th>
      <th width="150" align="center">当前频道</th>

      <th width="80" align="center">操作</th>
     </tr>
<?php	


		foreach($users as $userId=>$user){
			$user = ServerInterface::getInstance()->getServerRegistration($serverId, $userId);
			$cid =$user->getCurrentChanId();
			$data['all'][]=$user;
			if($cid<0){
				
			$data['noline'][]=$user;	
			}else{
			$data['online'][]=$user;
				
			}
				
			}
		
	if($vid==1){
			$v ='online';
	}elseif($vid==2){
			$v='noline';
	}else{
		
	$v='all';
		
	}

		//var_dump($data[$v]);
	if(!empty($data[$v])){
	foreach($data[$v] as $uid=>$user){
			
		?>
		<tr>
      <td align="center"><?php echo $user->getUserId();?></td>
      <td><?php echo $user->getName();?></td>
      <td align="center"><?php echo $user->getEmail();?></td>
	  <td align="center">
		<?php
		  $cha=$user->getCurrentChanId();
	  if(intval($cha)<0){
		  
		  echo "离线";
	  }elseif(intval($cha)==0){
		  echo "守候频道";
		  
	  }else{
		  $cid= intval($cha);
		  $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));

		$chs =$server->getChannelState($cid);
		echo $chs->getName();
		  
	  }
		?>
	  </td>

      <td align="center">
<a href="#" onclick="jq_server_reset_user_password(<?php echo $serverId;?>,<?php echo $userId; ?>);">重置密码</a> | <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_server_registration_remove(<?php echo $userId; ?>);}">删除</a> 
	 </td>
     </tr>
		
		<?php
	
		
	}
	
	}	
?>
</table>
<script>
var total=<?php echo count($data[$v]);?>
</script>
<?php
	}
	
	public static function db_admin_add()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;
		DBManager::getInstance()->addAdmin(strip_tags($_POST['name']), strip_tags($_POST['pw']), strip_tags($_POST['isGlobalAdmin']));
		MessageManager::echoAllErrors();
	}
	public static function server_user_add(){
		$sid =$_GET['sid'];
		$uname=$_POST['uname'];
		//$upwd=$_POST['pwd'];
	
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;
			try {
				ServerInterface::getInstance()->addUser($sid,$uname,null, null);
			
			} catch(Exception $exc) {

			}	
	}
	public static function server_member_add(){
		$mt4Ids = !empty($_POST['mt4Ids']) ? $_POST['mt4Ids'] : false;
		$stripMt4Ids = preg_replace('/[\"\[\]]/', '', $mt4Ids);
		$mt4IdsToArr = explode(',', $stripMt4Ids);
		$srid =$_GET['sid'];
		$cid =$_GET['cid'];
		$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
		//$server=ServerInterface::getInstance()->getServer(intval($srid));
		//$chs =$server->getChannel($cid);
		$chs =$server->getChannelState($cid);
		//var_dump($chs);die;
		$name =$chs->getName();
		//var_dump($name);die;
		$members= $chs->getmembers();
		$members =explode(",",$members);
		//var_dump($members);die;
		foreach($mt4IdsToArr as $uid){
			
			$members[]=$uid;
		}
		$msstring =implode(",",$members);
		//var_dump($chs);die;
		ServerInterface::getInstance()->updateChannel($srid,$cid,$name,$msstring);
		
	//	echo 1;die;
		
		$data = array(
		   'state' => 1,
		   'msg'  => '操作成功'
		);
echo json_encode($data);
return false;
	
	}
	
	public static function db_admin_remove()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		DBManager::getInstance()->removeAdminLogin($_POST['id']);
		MessageManager::echoAllErrors();
	}

	/**
	 * requires admin id 'aid' and group id 'gid' as _POST
	 */
	public static function db_admin_addToGroup()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		DBManager::getInstance()->addAdminToGroup($_POST['aid'], $_POST['gid']);
		MessageManager::echoAllErrors();
	}

	/**
	 * requires admin id 'aid' as _POST
	 */
	public static function db_admin_removeFromGroups()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		DBManager::getInstance()->removeAdminFromGroup($_POST['aid']);
		MessageManager::echoAllErrors();
	}

	/**
	 * requires group id 'aid' as _POST
	 */
	public static function db_admin_addToGroup_display()
	{
		if (!PermissionManager::getInstance()->serverCanEditAdmins())
			return ;

		$aid = intval($_POST['aid']);

		$admin = DBManager::getInstance()->getAdmin($aid);
		$groups = DBManager::getInstance()->getAdminGroups();

		echo 'Add ' . $admin['name'] . ' to group:<br/>';
		echo '<ul>';
		foreach ($groups AS $group) {
			echo '<li><a class="jqlink" onclick="jq_admin_addToGroup(' . $aid . ', ' . $group['id'] . ');">' . $group['name'] . '</a></li>';
		}
		echo '</ul>';
	}

	public static function meta_showDefaultConfig()
	{
		$config = ServerInterface::getInstance()->getDefaultConfig();
		echo '<table>';
		foreach ($config AS $key=>$value) {
			echo '<tr><td>'.$key.':</td><td>'.$value.'</td></tr>';
		}
		echo '</table>';
		MessageManager::echoAllErrors();
	}

	public static function server_create()
	{
		if (!PermissionManager::getInstance()->isGlobalAdmin())
			return ;

		echo ServerInterface::getInstance()->createServer();
	}

	public static function server_delete()
	{
		$serverId = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->isGlobalAdmin($serverId))
			return ;

		ServerInterface::getInstance()->deleteServer($serverId);
		SettingsManager::getInstance()->removeServerInformation($serverId);
	}

	public static function server_start()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanStartStop($_POST['sid']))
			return ;

		ServerInterface::getInstance()->startServer($_POST['sid']);
	}

	public static function server_stop()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanStartStop($_POST['sid']))
			return ;

		ServerInterface::getInstance()->stopServer($_POST['sid']);
	}

	public static function server_setSuperuserPassword()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanGenSuUsPW($_POST['sid']))
			return ;

		ServerInterface::getInstance()->setServerSuperuserPassword($_POST['sid'], $_POST['pw']);
	}

	public static function server_getRegistrations()
	{
		$serverId = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
			echo tr('permission_denied');
			MessageManager::echoAllMessages();
			exit();
		}
	if(!isset($_GET['najax'])){
		try {
		$pageIndex =intval($_POST['pageIndex'])-1;
		$pageSize =intval($_POST['pageSize']);
		//echo $pageSize;die;
			$curpage = $pageIndex*$pageSize;
			$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			$users = $server->getRegisteredUsers();
			
			unset($users[0]);//不能使用array_shift()这样会重置数组索引
			//var_dump($users);
			//echo $curpage;
			$users =array_slice($users,$curpage,$pageSize,true);
			
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="150">用户名</th>
	  <th width="150">昵称</th>
      <th width="150" align="center">当前频道</th>

      <th width="80" align="center">操作</th>
     </tr>
       
<?php
					foreach ($users AS $userId=>$userName) {
						
						//FIXME Ice version check, enum-index available? otherwise, one has to edit his slice file – actually, this fixme should be a general check, in install or general warning-disableable
						$user = ServerInterface::getInstance()->getServerRegistration($serverId, $userId);
						
						if($user->getUserId()!==0){
?>
   <tr>
      <td align="center"><?php echo $userId; ?></td>
      <td><a href="edituser.html?rec=edit&id=10"><?php echo $userName; ?></a></td>
      <td align="center"><a href="article.php?cat_id=1"><?php echo $user->getEmail();?></a></td>
	  <td align="center"><a href="article.php?cat_id=1"><?php
	  $cha=$user->getCurrentChanId();
	  if(intval($cha)<0){
		  
		  echo "离线";
	  }elseif(intval($cha)==0){
		  echo "守候频道";
		  
	  }else{
		  $cid= intval($cha);
		  $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));

		$chs =$server->getChannelState($cid);
		echo $chs->getName();
		  
	  }
	  

	  
	  

	  
	  ?></a></td>

      <td align="center">
<a href="#" onclick="jq_server_reset_user_password(<?php echo $serverId;?>,<?php echo $userId; ?>);">重置密码</a> | <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_server_registration_remove(<?php echo $userId; ?>);}">删除</a>     
	 </td>
     </tr>
<?php
}

}

?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		} catch(Murmur_ServerBootedException $exc) {
			echo '<div class="error">Server is not running</div>';
		}
		}else{
			$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			$users = $server->getRegisteredUsers();	
			array_shift($users);
			$i =count($users);
			echo json_encode($i);
			
			
			
			
		}
	}

	public static function server_onlineUsers_show()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->isAdminOfServer($_POST['sid'])) {
			echo tr('permission_denied');
			MessageManager::echoAllMessages();
			exit();
		}
		$canModerate = PermissionManager::getInstance()->serverCanModerate($_POST['sid']);

		$users = array();
		try {
			$users = ServerInterface::getInstance()->getServerUsersConnected($_POST['sid']);
?>
			<h2>Online Users</h2>
			<table id="mpi_table_onlineusers">
				<thead>
					<tr>
						<th class="col_sessId"><abbr title="Session">Sess</abbr> ID</th>
						<th class="col_regId"><abbr title="Registration">Reg</abbr> ID</th>
						<th class="col_uame">Username</th>

						<th class="col_isMuted"><abbr title="Was the user muted by an admin?">muted?</abbr></th>
						<th class="col_isDeafened"><abbr title="Was the user deafened by an admin?">deaf?</abbr></th>
						<th class="col_isSuppressed"><abbr title="Was the user suppressed (“muted” by channel ACL)?">suppressed</abbr></th>
						<th class="col_isSelfMuted"><abbr title="Did the user mute himself?">selfMuted</abbr></th>
						<th class="col_isSelfDeafened"><abbr title="Did the user deafen himself?">selfDeafened</abbr></th>

						<th class="col_timeOnline">time online</th>
						<th class="col_timeIdle">idle</th>
						<th class="col_bytesPerSecond"><abbr title="~Bytes per second – “Average transmission rate in bytes per second over the last few seconds.”">B/s</abbr></th>
						<th class="col_version"><abbr title="Client Version – This is either the stable version number (with major.minor.patchlevel), or the version number with release-version (e.g. snapshot version) in paranthesis">Version</abbr></th>
						<th class="col_comment">comment</th>
						<th class="col_address">address</th>
						<th class="col_isTcpOnly">TCPonly</th>

						<th class="col_actions">Actions</th>
					</tr>
				</thead>
				<tbody>
<?php				foreach ($users AS $user) {	?>
					<tr>
						<td class="col_sessId"><?php echo $user->getSessionId(); ?></td>
						<td class="col_regId">
							<?php
								$regId = $user->getRegistrationId();
								if ($regId !== -1) {
									echo $regId;
								}
							?>
						</td>
						<td id="user_name_<?php echo $user->sessionId; ?>" class="col_uame"><?php echo $user->name; ?></td>

						<td class="col_isMuted"><input id="user_mute_<?php echo $user->getSessionId(); ?>" class="jq_toggleable" type="checkbox" <?php if ($user->getIsMuted()) echo 'checked=""'; if(!$canModerate) echo 'disabled="disabled"'; ?>/></td>
						<td class="col_isDeafened"><input id="user_deaf_<?php echo $user->getSessionId(); ?>" class="jq_toggleable" type="checkbox" <?php if ($user->getIsDeafened()) echo 'checked=""'; if(!$canModerate) echo 'disabled="disabled"'; ?>/></td>
						<td class="col_isSuppressed"><input type="checkbox" <?php if ($user->getIsSuppressed()) echo 'checked=""'; ?> disabled="disabled"/></td>
						<td class="col_isSelfMuted"><input type="checkbox" <?php if ($user->getIsSelfMuted()) echo 'checked=""'; ?> disabled="disabled"/></td>
						<td class="col_isSelfDeafened"><input type="checkbox" <?php if ($user->getIsSelfDeafened()) echo 'checked=""'; ?> disabled="disabled"/></td>

						<td id="user_email_<?php echo $user->getSessionId(); ?>" class="col_timeOnline">
							<?php $on = $user->getOnlineSeconds(); if ($on > 59) { echo sprintf('%.0f', $on/60).'m'; } else { echo $on.'s'; } ?>
						</td>
						<td class="col_timeIdle">
							<?php $idle = $user->getIdleSeconds(); if ($idle > 59) { echo sprintf('%.0f', $idle/60).'m'; } else { echo $idle.'s'; } ?>
						</td>
						<td class="col_bytesPerSecond"><?php echo $user->getBytesPerSecond(); ?></td>
						<td class="col_version">
							<?php
								echo $user->getClientVersionAsString();
								if ($user->getClientVersionAsString() != $user->getClientRelease()) {
									echo ' (' . $user->clientOs() . $user->getClientRelease() . ')';
								}
							?>
						</td>
						<td id="userComment<?php echo $user->getSessionId(); ?>" class="col_comment comment userComment">
							<?php $commentClean = htmlspecialchars($user->getComment()); ?>
							<?php
								if (!empty($commentClean)) {
									if (strlen($commentClean) > 10) {
										?>
											<a title="Toggle display of full comment. HTML is escaped to ensure you can safely view it." href="javascript:toggleUserComment(<?php echo $user->getSessionId(); ?>);" style="float:left; margin-right:4px;">○</a>
										<?php
											}
										?>
										<div class="teaser">
											“<?php echo ((strlen($commentClean) > 10) ? substr($commentClean, 0, 10) . '…' : $commentClean); ?>“
										</div>
										<div class="complete" style="display:none;">
											<?php echo $commentClean; ?>
										</div>
										<script type="text/javascript">/*<![CDATA[*/
											// toggle display of user comment teaser <-> full
											function toggleUserComment(userSessionId)
											{
												jQuery('#userComment' + userSessionId + ' .teaser').css('display', (jQuery('#userComment' + userSessionId + ' .teaser').css('display')=='block'?'none':'block'));
												jQuery('#userComment' + userSessionId + ' .complete').css('display', (jQuery('#userComment' + userSessionId + ' .complete').css('display')=='block'?'none':'block'));
											}
											/*]]>*/
										</script>
									<?php
								}
							?>
						</td>
						<td class="col_address userAddress">
							<?php echo $user->getAddress()->__toString(); ?> <sup>(<a href="http://[<?php echo $user->getAddress(); ?>]">http</a>, <a href="http://www.db.ripe.net/whois?searchtext=<?php echo $user->getAddress(); ?>">lookup</a>)</sup>
							<?php if ($user->getAddress()->isIPv4()) { echo '<div>' . $user->getAddress()->toStringAsIPv4() . '</div>'; } ?>
						</td>
						<td class="col_isTcpOnly"><input type="checkbox" <?php echo $user->getIsTcpOnly()?'checked=""':''; ?> disabled="disabled"/></td>

						<td class="col_actions">
<?php
						if (PermissionManager::getInstance()->serverCanKick($_POST['sid']))
							echo '<a class="jqlink" onclick="jq_server_user_kick(' . $user->getSessionId() . ')">kick</a>';
?>
						</td>
					</tr>
<?php				}	?>
				</tbody>
			</table>
<?php
			if ($canModerate) {
?>
				<script type="text/javascript">/*<![CDATA[*/
					$('.jq_toggleable').click(
							function(event)
							{
								var id = $(this).attr('id');
								var sub = id.substring(0, id.lastIndexOf('_'));
								var id = id.substring(id.lastIndexOf('_')+1, id.length);
								switch (sub) {
									case 'user_mute':
										if ($(this).attr('checked')) {
											jq_server_user_mute(id);
										} else {
											jq_server_user_unmute(id);
										}

										break;
									case 'user_deaf':
										if ($(this).attr('checked')) {
											jq_server_user_deaf(id);
										} else {
											jq_server_user_undeaf(id);
										}
										break;
								}
							}
						);
					/*]]>*/
				</script>
<?php
			} // permission check: moderate
		} catch(Murmur_ServerBootedException $exc) {
			echo '<div class="error">Server is not running</div>';
		}
	}

	public static function server_regstration_remove()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanEditRegistrations($_POST['sid']))
			return ;

		ServerInterface::getInstance()->removeRegistration($_POST['sid'], $_POST['uid']);
	}
	
	public static function server_channel_remove()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanEditChannels($_POST['sid']))
			return ;

		ServerInterface::getInstance()->removeChannels($_POST['sid'], $_POST['aid']);
	}
	public static function server_member_remove(){
		$srid =intval($_POST['sid']);
		$cid =intval($_POST['cid']);
		$uid =intval($_POST['uid']);
		$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
		//$server=ServerInterface::getInstance()->getServer(intval($srid));
		//$chs =$server->getChannel($cid);
		$chs =$server->getChannelState($cid);
		$name =$chs->getName();
		$members =$chs->getMembers();
		$mbs =explode(",",$members);
		foreach( $mbs as $k=>$v) {
			if($uid == $v) unset($mbs[$k]);
		}
		$mstrings=implode(",",$mbs);
		ServerInterface::getInstance()->updateChannel($srid,$cid,$name,$mstrings);
		//var_dump($mbs);
		
	}
	
	
	public static function server_channel_add()
	{
		$sid = intval($_POST['sid']);
		$name =trim($_POST['name']);
		
		
		if (!PermissionManager::getInstance()->serverCanEditChannels($_POST['sid']))
			return ;
		
		ServerInterface::getInstance()->addChannels($sid,$name);
	}
	
	public static function server_channel_save()
	{
		$sid = intval($_POST['sid']);

		$cid =intval($_POST['cid']);
		$name =trim($_POST['name']);
		//var_dump($name);
	
		if (!PermissionManager::getInstance()->serverCanEditChannels($_POST['sid']))
			return ;
		

		 ServerInterface::getInstance()->updateChannel($sid,$cid,$name);
	
		
	}
	public static function server_regstration_genpw()
	{
		
		$serverId = intval($_POST['serverId']);
		$userId = intval($_POST['userId']);
		$newPw = $_POST['newPw'];
	
		if (!PermissionManager::getInstance()->serverCanEditRegistrations($_POST['serverId']))
			return ;
		//$reg = ServerInterface::getInstance()->getServerRegistration($serverId, $userId);
		//$reg->setPassword($newPw);
		//var_dump($reg);
		 ServerInterface::getInstance()->updateUserPw($serverId, $userId, $newPw);
	
		//ServerInterface::getInstance()->saveRegistration($reg);
		//echo $userId;die;
		//$reg = ServerInterface::getInstance()->getServerRegistration($serverId, $userId);
		
		//var_dump($reg);
		$data = array(
		   'state' => 1,
		   'msg'  => '操作成功'
		);
		echo json_encode($data);
		return false;
	}

	public static function server_user_mute()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanModerate($_POST['sid']))
			return ;

		ServerInterface::getInstance()->muteUser($_POST['sid'], $_POST['sessid']);
	}

	public static function server_user_unmute()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanModerate($_POST['sid']))
			return ;

		ServerInterface::getInstance()->unmuteUser($_POST['sid'], $_POST['sessid']);
	}

	public static function server_user_deaf()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanModerate($_POST['sid']))
			return ;

		ServerInterface::getInstance()->deafUser($_POST['sid'], $_POST['sessid']);
	}

	public static function server_user_undeaf()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->serverCanModerate($_POST['sid']))
			return ;

		ServerInterface::getInstance()->undeafUser($_POST['sid'], $_POST['sessid']);
	}

	public static function server_user_kick()
	{
		$_POST['sid'] = intval($_POST['sid']);
		if (PermissionManager::getInstance()->serverCanKick($_POST['sid']))
			ServerInterface::getInstance()->kickUser($_POST['sid'], $_POST['sessid']);
	}

	public static function server_bans_show()
	{
		$serverId = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->isAdminOfServer($serverId)) {
			echo tr('permission_denied');
			MessageManager::echoAllMessages();
			exit();
		}
		$bans = array();
		try {
			$bans = ServerInterface::getInstance()->getServerBans($serverId);
			echo '<h2>Bans</h2>';
			if (count($bans)==0) {
				echo 'no bans on this virtual server';
			} else {
?>
				<table>
					<thead>
						<tr>
							<th>username</th>
							<th>address</th>
							<th>bits</th>
							<th>hash</th>
							<th>reason</th>
							<th>banned at (until)</th>
							<th>actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($bans as $ban) { ?>
						<?php $ipAsString = HelperFunctions::int2ipAddress($ban->address); ?>
							<tr>
								<td><?php echo $ban->name; ?></td>
								<td><?php echo $ipAsString; ?></td>
								<td><?php echo $ban->bits; ?></td>
								<td><?php echo $ban->hash; ?></td>
								<td><?php echo $ban->reason; ?></td>
								<td><?php echo date('r', $ban->start) . ' (' . ($ban->duration != 0 ? date('r', $ban->duration) : 'unlimited') . ')'; ?></td>
								<td>
									<?php
										if (PermissionManager::getInstance()->serverCanBan($serverId)) {
											$banObj = MurmurBan::fromIceObject($ban);
											echo "<a class=\"jqlink\" onclick=\"if(confirm('Are you sure you want to remove this ban?')){jq_server_unban($serverId, '$ipAsString', $banObj->bits, '$banObj->name', '$banObj->hash', '$banObj->reason', $banObj->start, $banObj->duration);}\">remove</a>";
										}
									?>
							</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<br/>
				<p>
					<?php echo tr('info_ip_bits'); ?>
				</p>
<?php
			}
		} catch(Murmur_ServerBootedException $exc) {
			//TODO i18n
			echo 'Server is not running.';
		}
	}
	public static function server_ban_show()
	{
		$serverId = intval($_POST['serverId']);
		echo '<div class="ban_form">';
		echo '<table><thead><tr><th>IP</th><th>bits</th></tr></thead><tbody><tr><td><input type="text" name="ip" value=""/></td><td><input type="text" name="bits" value="32"/></td></tr></tbody></table>';
		echo '<a class="jqlink" onclick="' . "jq_server_ban($serverId, $('.ban_form input[name=ip]').val(), $('.ban_form input[name=bits]').val())" . '">add</a><br/>';
		echo '<br/>';
		echo tr('info_ip_bits');
		echo '</div>';
	}
	public static function server_ban()
	{
		$serverId = intval($_POST['serverId']);
		$ip       = strip_tags($_POST['ipmask']);
		$bits     = intval($_POST['bits']);
		if (strpos($ip, '.') === false) {
			$ip = intval($ip);
		} else {
			$ip = HelperFunctions::ip2int($ip);
		}
		ServerInterface::getInstance()->ban($serverId, $ip, $bits);
	}
	public static function server_unban()
	{
		$serverId     = intval($_POST['serverId']);
		$ip           = $_POST['ip'];
		$siBits       = intval($_POST['bits']);
		$username     = $_POST['name'];
		$hash         = $_POST['hash'];
		$reason       = $_POST['reason'];
		$siStart      = intval($_POST['start']);
		$siDuration   = intval($_POST['duration']);
		if (PermissionManager::getInstance()->serverCanBan($serverId)) {
			ServerInterface::getInstance()->unban($serverId, $ip, $siBits, $username, $hash, $reason, $siStart, $siDuration);
		}
	}

	public static function show_tree()
	{
		$sid = intval($_POST['sid']);
		if (!PermissionManager::getInstance()->isAdminOfServer($sid)) {
			echo tr('permission_denied');
			MessageManager::echoAllMessages();
			exit();
		}
		if(!isset($_GET['najax'])){
		$pageIndex =intval($_POST['pageIndex'])-1;
		$pageSize =intval($_POST['pageSize']);
		$curpage = $pageIndex*$pageSize;
         $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels();
	 //var_dump($data);
	 			unset($data[0]);//不能使用array_shift()这样会重置数组索引
			//var_dump($users);
			//echo $curpage;
			$data =array_slice($data,$curpage,$pageSize,true);
		
	 ?>
	 

	 <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     
	 <tr>
     <th width="120" align="left">频道号</th>
    <th align="left">频道名称</th>
     <th align="left">频道成员</th>    
     <th width="80" align="center">操作</th>
     </tr>

	 <?php
		 foreach($data as $k =>$row){
			// echo "<pre>";
			// echo $k;
			// var_dump($row->members);
			$num =explode(",",$row->members);
						//var_dump($num);
			if(empty($num[0])){
				
				unset($num[0]);
			}
			$n =count($num)>0?count($num):0;
			//echo $n;
			//if($k==0) continue;

		 ?>
		<tr>
       <td align='left'><?php echo $k; ?></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $n;?><a href='?page=user&sid=1&action=show_members&cid=<?php echo $row->id; ?>'> &nbsp;查看</a></td>
		<td align="center"><a href="./?page=channel&action=edit&sid=1&cid=<?php echo $row->id; ?>">编辑</a> | <a href="javascript:;" onclick="if(confirm('确定删除吗')){jq_server_channel_remove(<?php echo $row->id; ?>);}">删除</a></td>
     </tr>
	 <?php
      // ++$i;
		 }
		?>
		
		
		</table>

<?php
		
	
		}else{
			
			 $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels();
			 $num =count($data);
			 if( $num>0){
				 unset($data[0]);
				 $n =count($data);
				 if($n>0)$i=$n;
				 
			 }else{
				 
				 $i=0;
			 }
			 
			
			
			 echo json_encode($i);
		}
	}

	public static function show_acl()
	{
		//TODO: IMPLEMENT show_acl()
	}

	public static function server_config_get()
	{
		$_POST['sid'] = intval($_POST['sid']);
		ServerInterface::getInstance()->getServerConfig($_POST['sid']);
	}

	public static function server_config_show()
	{
		if(!isset($_POST['sid'])) return;
		$_POST['sid'] = intval($_POST['sid']);
		$conf = ServerInterface::getInstance()->getServerConfig($_POST['sid']);
		//TODO i18n
?>
		<h1>Server Config</h1>
		<p>For documentation, see your murmur.ini file (or <a href="#" rel="external">this</a> one in the repository, which may be newer than yours though)</p>
		<br/>
		<br/>
		<?php $canEdit = PermissionManager::getInstance()->serverCanEditConf($_POST['sid']);
			if ($canEdit) { ?>
			<p style="font-size:x-small;">(Double-click entries to edit them)</p>
		<?php } ?>
		<table>
			<tbody>
				<tr class="table_headline">
					<td colspan="2">General</td>
				</tr>
				<tr>
					<td>Password</td>
					<td class="jq_editable" id="jq_editable_server_conf_password"><?php echo $conf['password']; ?></td>
					<?php unset($conf['password']); ?>
				</tr>
				<tr>
					<td>Users</td>
					<td class="jq_editable" id="jq_editable_server_conf_users"><?php echo $conf['users']; ?></td>
					<?php unset($conf['users']); ?>
				</tr>
				<tr>
					<td>Timeout</td>
					<td class="jq_editable" id="jq_editable_server_conf_timeout"><?php echo $conf['timeout']; ?></td>
					<?php unset($conf['timeout']); ?>
				</tr>
				<tr>
					<td>Host</td>
					<td class="jq_editable" id="jq_editable_server_conf_host"><?php echo $conf['host']; ?></td>
					<?php unset($conf['host']); ?>
				</tr>
				<tr>
					<td>Port</td>
					<td class="jq_editable" id="jq_editable_server_conf_port"><?php echo $conf['port']; ?></td>
					<?php unset($conf['port']); ?>
				</tr>
				<?php
					if (isset($conf['defaultchannel'])) {
				?>
				<tr>
					<td>Default Channel</td>
					<td id="jq_editable_server_conf_defaultchannel">
						<?php
							$defaultChannelId = $conf['defaultchannel'];
							$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_POST['sid']));
							$defaultChannel = $server->getChannel($defaultChannelId);
							echo $defaultChannel->getName();

							// change default chan functionality
							$chanTree = $server->getTree();
							function treePrint(MurmurTree $tree, $first=true)
							{
								$subs = $tree->getSubChannels();
								if ($first) {
									echo '<div id="jq_editable_server_conf_defaultchannel_form">';
									//TODO i18n
									echo '<p>Select the channel unregistered and new users are to join when joining the server.</p>';
								}
								?>
									<ul>
										<li class="form_clickable_submit jslink" id="channel_<?php echo $tree->getRootChannel()->getId(); ?>"><?php echo $tree->getRootChannel()->getName(); ?></li>
										<?php
											if (!empty($subs)) {
												foreach ($subs as $subTree) {
													treePrint($subTree, false);
												}
											}
										?>
									</ul>
								<?php
								if ($first) {
									echo '</div>';
								}
							}
							treePrint($chanTree);
							unset($conf['defaultchannel']);
						?>
					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td>Welcometext</td>
					<td class="jq_editable" id="jq_editable_server_conf_welcometext"><?php echo $conf['welcometext']; ?></td>
					<?php unset($conf['welcometext']); ?>
				</tr>

				<tr class="table_headline">	<td colspan="2"></td></tr>
				<tr>
					<td>Bandwidth</td>
					<td class="jq_editable" id="jq_editable_server_conf_bandwidth"><?php echo $conf['bandwidth']; ?></td>
					<?php unset($conf['bandwidth']); ?>
				</tr>
				<?php
					if (isset($conf['obfuscate'])) {
				?>
				<tr>
					<td>Obfuscate IPs</td>
					<td id="jq_editable_server_conf_obfuscate">
						<input type="checkbox"<?php if ($conf['obfuscate'] == true) { echo ' checked="checked"'; } ?>/>
						<?php unset($conf['obfuscate']); ?>
					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td>Allowed Channelname <a href="http://en.wikipedia.org/wiki/Regular_expression#POSIX" rel="external">Regexp</a></td>
					<td class="jq_editable" id="jq_editable_server_conf_channelname"><?php echo $conf['channelname']; ?></td>
					<?php unset($conf['channelname']); ?>
				</tr>
				<tr>
					<td>Allowed Username <a href="http://en.wikipedia.org/wiki/Regular_expression#POSIX" rel="external">Regexp</a></td>
					<td class="jq_editable" id="jq_editable_server_conf_username"><?php echo $conf['username']; ?></td>
					<?php unset($conf['username']); ?>
				</tr>
				<?php
					if (isset($conf['certrequired'])) {
				?>
				<tr>
					<td>UserCert Required</td>
					<td id="jq_editable_server_conf_certrequired">
						<input type="checkbox"<?php if ($conf['certrequired'] == true) { echo ' checked="checked"'; } ?>/>
						<?php unset($conf['certrequired']); ?>
					</td>
				</tr>
				<?php
					}
					if (isset($conf['textmessagelength'])) {
				?>
				<tr>
					<td>Textmessagelength</td>
					<td class="jq_editable" id="jq_editable_server_conf_textmessagelength"><?php echo $conf['textmessagelength']; ?></td>
					<?php unset($conf['textmessagelength']); ?>
				</tr>
				<?php
					}
					if (isset($conf['allowhtml'])) {
				?>
				<tr>
					<td>Allow HTML</td>
					<td id="jq_editable_server_conf_allowhtml">
						<input type="checkbox"<?php if ($conf['allowhtml'] == true) { echo ' checked="checked"'; } ?>/>
						<?php  unset($conf['allowhtml']); ?>
					</td>
				</tr>
				<?php
					}
					if (isset($conf['rememberchannel'])) {
				?>
				<tr>
					<td><abbr title="Remember last channel for registered users; Users will be moved to this channel on connection.">Remember Channel</abbr></td>
					<td id="jq_editable_server_conf_rememberchannel">
						<input type="checkbox"<?php if ($conf['rememberchannel'] == true) { echo ' checked="checked"'; } ?>/>
						<?php unset($conf['rememberchannel']); ?>
					</td>
				</tr>
				<?php
					}
					if (isset($conf['bonjour'])) {
				?>
				<tr>
					<td><abbr title="(service for server discovery on LAN)">Bonjour</abbr></td>
					<td id="jq_editable_server_conf_bonjour">
						<input type="checkbox"<?php if ($conf['bonjour'] == true) { echo ' checked="checked"'; } ?>/>
						<?php unset($conf['bonjour']); ?>
					</td>
				</tr>
				<?php
					}
				?>

				<tr class="table_headline">
					<td colspan="2">Server Registration</td>
				</tr>
				<tr>
					<td>registerhostname</td>
					<td class="jq_editable" id="jq_editable_server_conf_registerhostname"><?php echo $conf['registerhostname']; unset($conf['registerhostname']); ?></td>
				</tr>
				<tr>
					<td>registername</td>
					<td class="jq_editable" id="jq_editable_server_conf_registername"><?php echo $conf['registername']; unset($conf['registername']); ?></td>
				</tr>
				<tr>
					<td>registerpassword</td>
					<td class="jq_editable" id="jq_editable_server_conf_registerpassword"><?php echo $conf['registerpassword']; unset($conf['registerpassword']); ?></td>
				</tr>
				<tr>
					<td>registerurl</td>
					<td class="jq_editable" id="jq_editable_server_conf_registerurl"><?php echo $conf['registerurl']; unset($conf['registerurl']); ?></td>
				</tr>
				<?php
					if (!empty($conf)) {
						?>
							<tr class="table_headline">	 <td colspan="2">Misc</td></tr>
						<?php
					}
					foreach ($conf AS $key=>$val) {
						?>
									<tr>
										<td><?php echo $key; ?></td>
										<td class="jq_editable" id="jq_editable_server_conf_<?php echo $key; ?>"><?php echo $val; ?></td>
									</tr>
						<?php
					}
				?>
			</tbody>
		</table>
<?php
		if ($canEdit) {
?>
			<script type="text/javascript">/*<![CDATA[*/
				var currentServerId = <?php echo $_POST['sid']; ?>;
				function jq_server_conf_update(key, newValue)
				{
				  $.post('./?ajax=server_config_update',
							{ 'sid': currentServerId, 'key': key, 'value': newValue.toString() },
							function(data) {
								if (data.length > 0) {
									alert('Error: ' + data);
								}
								jq_server_config_show(currentServerId);
							}
						);
				}
				jQuery('#jq_editable_server_conf_allowhtml input:checkbox')
					.click(
						function(ev) {
							jq_server_conf_update('allowhtml', jQuery(this).attr('checked'));
						}
					);
				jQuery('#jq_editable_server_conf_obfuscate input:checkbox')
					.click(
						function(ev) {
							jq_server_conf_update('obfuscate', jQuery(this).attr('checked'));
						}
					);
				jQuery('#jq_editable_server_conf_rememberchannel input:checkbox')
					.click(
						function(ev) {
							jq_server_conf_update('rememberchannel', jQuery(this).attr('checked'));
						}
					);
				jQuery('#jq_editable_server_conf_bonjour input:checkbox')
					.click(
						function(ev) {
							jq_server_conf_update('bonjour', jQuery(this).attr('checked'));
						}
					);
				jQuery('#jq_editable_server_conf_certrequired input:checkbox')
					.click(
						function(ev) {
							jq_server_conf_update('certrequired', jQuery(this).attr('checked'));
						}
					);
				function jq_editable_server_conf_onSubmit(obj, content)
				{
					var id = obj.attr('id');
					var subId = id.substring(id.lastIndexOf('_')+1, id.length);
					jq_server_conf_update(subId, content.current);
				}
			/*	function jq_editable_server_conf_text2textarea(key)
				{
				  jQuery('#jq_editable_server_conf_'+key).editable('destroy').editable({
						'type': 'textarea',
						'submit': 'save',
						'cancel':'cancel',
						'editBy': 'dblclick',
						'onSubmit': function(content){ jq_editable_server_conf_onSubmit($(this), content); }
					});
				}*/
			/*	jQuery('.jq_editable')
					.editable({
						'type': 'text',
						'submit': 'save',
						'cancel':'cancel',
						'editBy': 'dblclick',
						'onSubmit': function(content){ jq_editable_server_conf_onSubmit($(this), content); }
					});
				jq_editable_server_conf_text2textarea('welcometext');
				jq_editable_server_conf_text2textarea('certificate');
				jq_editable_server_conf_text2textarea('key');*/

				// default channel editable:
		jQuery('#jq_editable_server_conf_defaultchannel_form').ready(function(){
			jQuery("#jq_editable_server_conf_defaultchannel_form").dialog({
				title: 'Select default channel',
				autoOpen: false,
				height: 'auto',
				width: 'auto',
				modal: true,
				buttons: {
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
			jQuery('#jq_editable_server_conf_defaultchannel_form .form_clickable_submit').click(function(event){
					var id = jQuery(this).attr('id');
					var channelId = id.substr(id.indexOf('_')+1);
					$.post(
						'./?ajax=server_config_update',
						{ 'sid': currentServerId, 'key': 'defaultchannel', 'value': channelId },
						function(data) {
							jq_server_config_show(currentServerId);
							jQuery('#jq_editable_server_conf_defaultchannel_form').dialog('close').remove();
						}
					);
				});
			jQuery('#jq_editable_server_conf_defaultchannel')
				.dblclick(function(){
					jQuery('#jq_editable_server_conf_defaultchannel_form').dialog('open');
				});
		});
				/*]]>*/
			</script>
<?php
		}
	}

	/**
	 * params:
	 *  sid: serverId
	 *  key: config setting key
	 *  value: new value
	 */
	public static function server_config_update()
	{
		$eiServerId = intval($_POST['sid']);
		if (PermissionManager::getInstance()->serverCanEditConf($eiServerId)) {
			if (isset($eiServerId) && isset($_POST['key']) && isset($_POST['value'])) {
				ServerInterface::getInstance()->setServerConfigEntry($eiServerId, $_POST['key'], $_POST['value']);
			} else {
				echo 'missing var';
			}
		}
	}

	public static function server_user_updateUsername()
	{
		$_POST['sid'] = intval($_POST['sid']);
		$_POST['uid'] = intval($_POST['uid']);
		if (PermissionManager::getInstance()->serverCanEditRegistrations($_POST['sid'])) {
			ServerInterface::getInstance()->updateUserName($_POST['sid'], $_POST['uid'], $_POST['newValue']);
		}
	}

	public static function server_user_updateEmail()
	{
		$_POST['sid'] = intval($_POST['sid']);
		$_POST['uid'] = intval($_POST['uid']);
		if (PermissionManager::getInstance()->serverCanEditRegistrations($_POST['sid'])) {
			ServerInterface::getInstance()->updateUserEmail($_POST['sid'], $_POST['uid'], $_POST['newValue']);
		}
	}

	public static function server_user_updateComment()
	{
		$serverId = intval($_POST['sid']);
		$userId = intval($_POST['uid']);
		$newValue = $_POST['newValue'];
		if (PermissionManager::getInstance()->serverCanEditRegistrations($serverId)) {
			ServerInterface::getInstance()->updateUserComment($serverId, $userId, $newValue);
		}
	}

	public static function server_user_updateHash()
	{
		$_POST['sid'] = intval($_POST['sid']);
		$_POST['uid'] = intval($_POST['uid']);
		if (PermissionManager::getInstance()->serverCanEditRegistrations($_POST['sid'])) {
			ServerInterface::getInstance()->updateUserHash($_POST['sid'], $_POST['uid'], $_POST['newValue']);
		}
	}

	public static function server_user_updateAvatar()
	{
		$serverId = intval($_POST['sid']);
		$userId = intval($_POST['uid']);
		$newValue = $_POST['newValue']=='null'?array():$_POST['newValue'];
		if (PermissionManager::getInstance()->serverCanEditRegistrations($serverId)) {
			ServerInterface::getInstance()->updateUserTexture($serverId, $userId, $newValue);
		}
	}

	public static function meta_server_information_edit()
	{
		$_POST['serverid'] = intval($_POST['serverid']);
		if (!PermissionManager::getInstance()->serverCanEditConf($_POST['serverid']))
			return ;

		$server = SettingsManager::getInstance()->getServerInformation($_POST['serverid']);

		echo '<div>';
		if ($server === null) {
			echo 'new:<br/>';
			$server['name']              = '';
			$server['allowlogin']        = true;
			$server['allowregistration'] = true;
			$server['forcemail']         = true;
			$server['authbymail']        = false;
		}
		echo	'<table>';
		echo		'<tr><td>name</td>'
						.'<td><input type="text" id="meta_server_information_name" name="meta_server_information_name" value="'
						.$server['name'].'" /></td></tr>';
		echo		'<tr><td>Allow Login</td>'
						.'<td><input type="checkbox" id="meta_server_information_allowlogin" name="meta_server_information_allowlogin"'
						.($server['allowlogin'] ? ' checked="checked"' : '').'" /></tr>';
		echo		'<tr><td>Allow Registration</td>'
						.'<td><input type="checkbox" id="meta_server_information_allowregistration" name="meta_server_information_allowregistration"'
						.($server['allowregistration'] ? ' checked="checked"' : '').'" /></tr>';
		echo		'<tr><td>Force eMail</td>'
						.'<td><input type="checkbox" id="meta_server_information_forcemail" name="meta_server_information_forcemail"'
						.($server['forcemail'] ? ' checked="checked"' : '').'" /></tr>';
		echo		'<tr><td>Auth by Mail</td>'
						.'<td><input type="checkbox" id="meta_server_information_authbymail" name="meta_server_information_authbymail"'
						.($server['authbymail'] ? ' checked="checked"' : '').'" /></tr>';
		echo	'</table>';
		echo	'<input type="button" value="update" onclick="jq_meta_server_information_update(' . $_POST['serverid'] . ');" />';
		echo	'<input type="button" value="cancel" onclick="$(\'#jq_information\').html(\'\');" />';
		echo '</div>';
	}

	public static function meta_server_information_update()
	{
		$serverId = isset($_POST['serverid'])?intval($_POST['serverid']):null;
		// user has rights?
		if (PermissionManager::getInstance()->serverCanEditConf($serverId)) {
			if ($serverId != null
					&& isset($_POST['name'])
					&& isset($_POST['allowlogin'])
					&& isset($_POST['allowregistration'])
					&& isset($_POST['forcemail'])
					&& isset($_POST['authbymail'])) {
				$serverId = intval($_POST['serverid']);
				$name = $_POST['name'];
				$allowLogin = $_POST['allowlogin'];
				$allowRegistration = $_POST['allowregistration'];
				$forcemail = $_POST['forcemail'];
				$authByMail = $_POST['authbymail'];

				SettingsManager::getInstance()->setServerInformation($serverId, $name, $allowLogin, $allowRegistration, $forcemail, $authByMail);
			} else {
				MessageManager::addError(TranslationManager::getInstance()->getText('error_missing_values'));
			}
		} else {
			MessageManager::addError('You don’t have permission to do this.');
		}
	}
        public static function server_operator_add(){
                $sid =$_GET['sid'];
                $account=$_POST['account'];
                $passwd=$_POST['passwd'];
                $name=$_POST['name'];
                $comment=$_POST['comment'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $previlege='r,w';
                $type=1;
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        if (!SessionManager::getInstance()->isAdmin())
                                  throw new Exception("请用管理员登录.");
                        $res = MysqlInterface::addOperator(1,$type,$account,$passwd,$name, $comment, $email,$phone,$previlege);

                } catch(Exception $exc) {
                        echo $exc->getMessage();
                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_operator_update(){
                $id =$_GET['id'];
                $passwd=$_POST['passwd'];
                if ($passwd == '')
                     $passwd = null;
                $name=$_POST['name'];
                $comment=$_POST['comment'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
			if (!SessionManager::getInstance()->isAdmin())
				throw new Exception("请用管理员登录.");

                        $res = MysqlInterface::updateOperator($id,null,$passwd,$name,$comment,$email,$phone,null);

                } catch(Exception $exc) {

                        echo $exc->getMessage();
                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_operator_remove(){
                $id =$_GET['id'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        if (!SessionManager::getInstance()->isAdmin())
				throw new Exception("请用管理员登录.");

                        $res = MysqlInterface::deleteOperatorById($id);

                } catch(Exception $exc) {

                        echo $exc->getMessage();
                }
                if ($res > 0)
                    echo "succeed!";
        }
	public static function server_getOperators()
	{
		//$serverId = intval($_POST['sid']);
		//if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
		
	        if(!isset($_GET['najax'])){
		       try {
			       if (!SessionManager::getInstance()->isAdmin())
				       throw new Exception("请用管理员登录.");


			       $pageIndex =intval($_POST['pageIndex'])-1;
			       $pageSize =intval($_POST['pageSize']);
			       //echo $pageSize;die;
			       $curpage = $pageIndex*$pageSize;
			       //$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			       //$users = $server->getRegisteredUsers();
			       $users = MysqlInterface::getOperatorsByAdmin();

			       //unset($users[0]);//不能使用array_shift()这样会重置数组索引
			       //var_dump($users);
			       //echo $curpage;
			       $users =array_slice($users,$curpage,$pageSize,true);

		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="20" align="center">姓名</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="20" align="center">年卡数</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>


      <td align="center">
             <a href="./?page=operator&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | <a href="./?page=operator&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;
" onclick="if(confirm('确定删除用户?')){jq_operator_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		        } catch(Exception $exc) {
			        echo $exc->getMessage();
		        }
	       }else{
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			$users = MysqlInterface::getOperatorsByAdmin();	
			//array_shift($users);
			$i =count($users);
			echo json_encode($i);
	       } 		
	}
        public static function server_operator_search()
        {
                try{
                        if (!SessionManager::getInstance()->isAdmin())
				       throw new Exception("请用管理员登录.");


			$value =addslashes($_POST['value']);
			//echo $kw;
			$type =intval($_POST['type']);
			$users = MysqlInterface::searchOperatorsByAdmin($type, $value);
			$total = count($users);
?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="20" align="center">姓名</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="20" align="center">年卡数</th>
      <th width="80" align="center">操作</th>

     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>
      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>

      <td align="center">
             <a href="./?page=operator&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | <a href="./?page=operator&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;
" onclick="if(confirm('确定删除用户?')){jq_operator_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               }catch(Exception $exc)
                    {
                               echo $exc->getMessage();
                    }

        }
        

        public static function server_record_add(){
                $sid =$_GET['sid'];
                $parentId =$_GET['uid'];
                $operatorId=$_POST['operatorId'];
                $allCards=$_POST['number'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::addBill($operatorId,0,$allCards);//enterpriseId is just set to 0

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_bill_update(){
                $id =$_GET['id'];
                $operatorId=$_POST['operatorId'];
                $allCards=$_POST['number'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::updateBillByAdmin($id, $operatorId, $allCards);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_bill_remove(){
                $id =$_GET['id'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::deleteBillById($id);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
	public static function server_getRecords()
	{
		//$serverId = intval($_POST['sid']);
		//if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
	        if(!isset($_GET['najax'])){
		       try {
		       $pageIndex =intval($_POST['pageIndex'])-1;
		       $pageSize =intval($_POST['pageSize']);
		       //echo $pageSize;die;
			$curpage = $pageIndex*$pageSize;
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			//$users = $server->getRegisteredUsers();
			$records = MysqlInterface::getRecordsByAdmin();
			
		//	unset($users[0]);//不能使用array_shift()这样会重置数组索引
	        //		var_dump($users);
			//echo $curpage;
			$records =array_slice($records,$curpage,$pageSize,true);
			
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">记录号</th>
      <th width="30" align="center">代理商编号</th>
      <th width="30" align="center">代理商姓名</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($records AS $record) {
						
?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['toId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除记录?')){jq_record_remove(<?php echo $record['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		        } catch(Exception $exc) {
			        echo '<div class="error">Server is not running</div>';
		        }
	       }else{
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			$records = MysqlInterface::getRecordsByAdmin();	
			//array_shift($users);
			$i =count($records);
			echo json_encode($i);
	       } 		
	}
        public static function server_record_search()
        {
                $value =addslashes($_POST['value']);
                //echo $kw;
                $type =intval($_POST['type']);
                $records = MysqlInterface::searchRecordsByAdmin($type, $value);
                $total = count($records);
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="20" align="center">记录号</th>
      <th width="30" align="center">代理商编号</th>
      <th width="30" align="center">代理商姓名</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($records AS $record) {
						
?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['toId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>
      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除账单?')){jq_bill_remove(<?php echo $record['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               
        
       }

        public static function server_record_file_output()
        {
                try{
			if (!SessionManager::getInstance()->isAdmin())
				throw new Exception("请用管理员登录.");


			$value =addslashes($_POST['value']);
			$type =intval($_POST['type']);
			$records = MysqlInterface::searchRecordsByAdmin($type, $value);                 
			$total = count($records);

			$fp = fopen('php://output', 'a');

			$head = array('账号', '密码', '邮箱', '电话', '备注');
			foreach ($head as $i => $v) {
				// CSV的Excel支持GBK编码，一定要转换，否则乱码
				$head[$i] = iconv('utf-8', 'gbk', $v);
			}

			// 将数据通过fputcsv写到文件句柄
			fputcsv($fp, $head);
			// 计数器
			$cnt = 0;
			// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
			$limit = 100000;

			foreach($records as $row)
			{
				$cnt ++;
				if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
					ob_flush();
					flush();
					$cnt = 0;
				}

				foreach ($row as $i => $v) {
					$row[$i] = iconv('utf-8', 'gbk', $v);
				}
				fputcsv($fp, $row);
			}
			// Create new PHPExcel object
			/*$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("allptt")
			->setLastModifiedBy("allptt")
			->setTitle("企业用户表")
			->setSubject("")
			->setDescription("企业用户表")
			->setKeywords("企业 用户")
			->setCategory("表单");


			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Hello')
			->setCellValue('B2', 'world!')
			->setCellValue('C1', 'Hello')
			->setCellValue('D2', 'world!');

			// Miscellaneous glyphs, UTF-8
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A4', 'Miscellaneous glyphs')
			->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç')
			->setCellValue('A6', '吴占涛');

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('企业用户');


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			 */
			// ob_end_clean(); 
			$filename = "账单名单";
			// Redirect output to a client’s web browser (Excel2007)
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			//header('Content-Disposition: attachment;filename="01simple.xlsx"');
			//header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			//header('Cache-Control: max-age=1');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename.'.csv');
			header('Cache-Control: max-age=0');
			// If you're serving to IE over SSL, then the following may be needed
			//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			//header ('Pragma: public'); // HTTP/1.0

			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter->save('php://output');
			exit;
                } catch (Exception $exc)
                {
                        echo $exc->getMessage();
                }

	}

        public static function server_dispatcher_add()
        {
                $operatorId = $_POST['operatorId'];
                $toType = 2;
                $cardNum = intval($_POST['cardNum']);
                $availableCards =intval( $_POST['availableCards'] );
                $cost = $_POST['cost'];
                $fromId = 1;
                $fromType = 1;
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {

                        $res = MysqlInterface::addAdminToOperatorDispatcher($operatorId,$cardNum+$availableCards);
                        $res = MysqlInterface::addRecord($fromId,$fromType,$operatorId, $toType,$cardNum,$cost);

                } catch(Exception $exc) {

                }
		echo "succeed!";
                
        }

        public static function server_checkOperator()
        {
                $account = $_POST['account'];
                $res = MysqlInterface::checkIfOperatorExist($account);
                if ($res)
                    echo "false";
                else 
                    echo "true";
        }


}

