<?php
/**
 * Ajax functionality
 * @author ztwu 
 */

require_once dirname(__FILE__).'/ajax.ajax.php';

/**
 * ajax functionality, functions for the admin section
 * @author ztwu
 */
class Ajax_Enterprise extends Ajax
{
    public static function getPage()
	{
		TemplateManager::parseTemplate($_GET['page']);
	}
	
	public static function server_user_add(){
		$sid =intval($_GET['sid']);
		$entId =intval($_GET['entId']);
		$uname=$_POST['uname'];
		$pwd=$_POST['pwd'];
		$email=$_POST['email'];
		$comment=$_POST['comment'];
		$phone=$_POST['phone'];
		$nick=$_POST['nick'];
		$type=intval($_GET['type']);
		$fenceAlarm=$_GET['fenceAlarm'];
	
		if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;
		try {
            if(MysqlInterface::checkIfEnterpriseCanCreateUser($entId, $type))
            {
			    $userid = ServerInterface::getInstance()->addUser($sid,$entId,$uname,$pwd,$nick,$comment,$email,$phone,$fenceAlarm, $type);
                MysqlInterface::chargeEnterpriseCardNum($entId, $type);
			}
            else{
                echo "卡余额不足，请联系代理商购买.";
            }
		} catch(Exception $exc) {
            echo $exc->getMessage();
		}	
	}
    public static function server_user_update(){
        $sid =intval($_GET['sid']);
        $entId =intval($_GET['entId']);
        $uid = intval($_GET['uid']);
        $uname=$_POST['uname'];
        $pwd=$_POST['pwd'];
        $email=$_POST['email'];
        $comment=$_POST['comment'];
        $phone=$_POST['phone'];
        $nick=$_POST['nick'];

        $fenceAlarm=$_GET['fenceAlarm'];
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

        try {
            $res = ServerInterface::getInstance()->updateUser($sid,$entId,$uid, $uname,$pwd,$nick,$comment,$email,$phone,$fenceAlarm);
        } catch(Exception $exc) {
            echo $exc->getMessage();
        }	
    }
    public static function server_user_renew(){
        $sid =intval($_GET['sid']);
        $entId =intval($_GET['entId']);
        $uid = intval($_POST['uid']);
        $type=intval($_GET['type']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

        try {
            if(MysqlInterface::checkIfEnterpriseCanCreateUser($entId, $type))
            {
                $res = ServerInterface::getInstance()->renewUser($sid,$entId,$uid,$type);
                MysqlInterface::chargeEnterpriseCardNum($entId, $type);
            }
            else{
                echo "卡余额不足，请联系代理商购买.";
            }
        } catch(Exception $exc) {
            echo $exc->getMessage();
        }	
    }
    public static function server_set_member_level(){
        $sid =intval($_GET['sid']);
        $entId =intval($_GET['entId']);
        $uid = intval($_GET['uid']);
        $channelId=intval($_GET['cid']);
        $level=intval($_GET['level']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

        try {
            $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid));
            $server->setChannelMemberLevel($entId, $channelId, $uid, $level); 
        } catch(Exception $exc) {
            echo $exc->getMessage();
        }	
    }
    public static function server_member_add(){
        $mt4Ids = !empty($_POST['mt4Ids']) ? $_POST['mt4Ids'] : false;
        $stripMt4Ids = preg_replace('/[\"\[\]]/', '', $mt4Ids);
        $mt4IdsToArr = explode(',', $stripMt4Ids);
        $srid =$_GET['sid'];
        $cid =intval($_GET['cid']);
        $entId =intval($_GET['entId']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

        $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
        $server->addChannelMembers( $entId, $cid, $stripMt4Ids);

        $data = array(
                'state' => 1,
                'msg'  => '操作成功'
                );
        echo json_encode($data);
        return false;

    }
    public static function server_getRegistrations_en()
	{
		$serverId = intval($_POST['sid']);
		$entId = intval($_GET['entId']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

		if(!isset($_GET['najax'])){
		try {
            $pageIndex =intval($_POST['pageIndex'])-1;
            $pageSize =intval($_POST['pageSize']);
            $curpage = $pageIndex*$pageSize;
            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $usersStr = $server->getRegisteredUserIds($entId, "");

            $users = explode(",", $usersStr);
            if(count($users) > 1)
                array_pop($users);
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">User ID</th>
      <th width="50">Account</th>
      <th width="50">Nick</th>
      <th width="50" align="center">CurrentChannel</th>
      <th width="50" align="center">Alarm</th>

      <th width="50">Expire Time</th>
      <th width="180" align="center">Operation</th>
     </tr>
       
<?php
            foreach ($users AS $userId ) {
                $user = ServerInterface::getInstance()->getServerRegistration($serverId,$entId, $userId);
                if($user->getUserId()!==0){
?>
   <tr>
      <td align="center"><?php echo $userId; ?></td>
      <td align="center"><a href="#"><?php echo $user->getAccount(); ?></a></td>
      <td align="center"><a href="#"><?php echo $user->getName(); ?></a></td>
      <td align="center"><a href="#"><?php
	  $cha=$user->getCurrentChanId();
	  if(intval($cha)<0){
		  
		  echo "Offline";
	  }elseif(intval($cha)==0){
		  echo "";
		  
      }else{
          $cid= intval($cha);
          $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));

          $chs =$server->getChannelState($entId, $cid);
          echo $chs->getName();

	  }
	  
	  ?></a></td>
      <td align="center"><a href="#">
      <?php 
          $fenceAlarm =  $user->getFenceAlarm(); 
          if($fenceAlarm == "1")
              echo "Yes";
          else
              echo "No";
         
      ?>
      </a></td>

      <td align="center"><a href="#">
      <?php 
          $time =  $user->getExpireTime(); 
          if($time == 2147483647)
              echo "Permanent";
          else
          {
              $timeN = intval($time);
              $now = time(NULL);
              if($timeN - $now< 15*24*60*60)
                  echo "<span style='color:red;'>".date("Y-m-d H:i:s",$time)."</span>";
              else
                  echo date("Y-m-d H:i:s",$time);
          }
         
      ?>
      </a></td>
      <td align="center">
<?php if($time != 2147483647) { ?><a href="?page=user&sid=1&action=renew&uid=<?php echo $userId; ?>" >Renew</a> | <?php } ?> <a href="?page=user&sid=1&action=edit&uid=<?php echo $userId; ?>" >Edit</a> | <a href="javascript:;" onclick="window.wxc.xcConfirm('Are you sure to remove?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_server_registration_remove(<?php echo $userId; ?>);}})">Remove</a> | <a href="?page=videos&sid=1&action=show_videos&uid=<?php echo $userId; ?>">Video</a> | <a href="?page=photos&sid=1&action=show_photos&uid=<?php echo $userId; ?>" >Photo</a> | <a href="?page=friends&sid=1&action=show_friends&uid=<?php echo $userId; ?>" >Friend</a>
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
            $usersStr = $server->getRegisteredUserIds($entId);	
            $users = explode(",", $usersStr);
            if(count($users) > 1)
                array_pop($users);
            $i =count($users);
            echo json_encode($i);
        }
	}
	public static function server_getRegistrations()
	{
		$serverId = intval($_POST['sid']);
		$entId = intval($_GET['entId']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

		if(!isset($_GET['najax'])){
		try {
            $pageIndex =intval($_POST['pageIndex'])-1;
            $pageSize =intval($_POST['pageSize']);
            $curpage = $pageIndex*$pageSize;
            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $usersStr = $server->getRegisteredUserIds($entId, "");

            $users = explode(",", $usersStr);
            if(count($users) > 1)
                array_pop($users);
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="50">账号</th>
      <th width="50">昵称</th>
      <th width="50" align="center">当前频道</th>
      <th width="50" align="center">告警提醒</th>

      <th width="50">到期时间</th>
      <th width="180" align="center">操作</th>
     </tr>
       
<?php
            foreach ($users AS $userId ) {
                $user = ServerInterface::getInstance()->getServerRegistration($serverId,$entId, $userId);
                if($user->getUserId()!==0){
?>
   <tr>
      <td align="center"><?php echo $userId; ?></td>
      <td align="center"><a href="#"><?php echo $user->getAccount(); ?></a></td>
      <td align="center"><a href="#"><?php echo $user->getName(); ?></a></td>
      <td align="center"><a href="#"><?php
	  $cha=$user->getCurrentChanId();
	  if(intval($cha)<0){
		  
		  echo "离线";
	  }elseif(intval($cha)==0){
		  echo "守候频道";
		  
      }else{
          $cid= intval($cha);
          $server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));

          $chs =$server->getChannelState($entId, $cid);
          echo $chs->getName();

	  }
	  
	  ?></a></td>
      <td align="center"><a href="#">
      <?php 
          $fenceAlarm =  $user->getFenceAlarm(); 
          if($fenceAlarm == "1")
              echo "是";
          else
              echo "否";
         
      ?>
      </a></td>

      <td align="center"><a href="#">
      <?php 
          $time =  $user->getExpireTime(); 
          if($time == 2147483647)
              echo "永久用户";
          else
          {
              $timeN = intval($time);
              $now = time(NULL);
              if($timeN - $now< 15*24*60*60)
                echo "<span style='color:red;'>".date("Y-m-d H:i:s",$time)."</span>";
              else
                echo date("Y-m-d H:i:s",$time);
          }
         
      ?>
      </a></td>
      <td align="center">
<?php if($time != 2147483647) { ?><a href="?page=user&sid=1&action=renew&uid=<?php echo $userId; ?>" >续卡</a> | <?php } ?> <a href="?page=user&sid=1&action=edit&uid=<?php echo $userId; ?>" >编辑</a> | <a href="javascript:;" onclick="window.wxc.xcConfirm('确定删除用户?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_server_registration_remove(<?php echo $userId; ?>);}})">删除</a> | <a href="?page=videos&sid=1&action=show_videos&uid=<?php echo $userId; ?>">视频</a> | <a href="?page=photos&sid=1&action=show_photos&uid=<?php echo $userId; ?>" >图片</a> | <a href="?page=friends&sid=1&action=show_friends&uid=<?php echo $userId; ?>" >联系人</a>
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
            $usersStr = $server->getRegisteredUserIds($entId);	
            $users = explode(",", $usersStr);
            if(count($users) > 1)
                array_pop($users);
            $i =count($users);
            echo json_encode($i);
        }
	}
    public static function server_getUserList()
	{
		$serverId = intval($_POST['sid']);
		$entId = intval($_GET['entId']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

        if(!isset($_GET['najax'])){
            try {
                $pageIndex =intval($_POST['pageIndex'])-1;
                $pageSize =intval($_POST['pageSize']);
                //echo $pageSize;die;
                $curpage = $pageIndex*$pageSize;
                $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
                $usersStr = $server->getRegisteredUserIds($entId, "");
                $users = explode(",", $usersStr);
                if(count($users) > 1)
                    array_pop($users);
?>
    <ul>  
<?php
                foreach ($users AS $userId) {
                    $user = ServerInterface::getInstance()->getServerRegistration($serverId,$entId, $userId);
						
                    if($user->getUserId()!==0){
?>
   <li><?php echo $user->getName(); ?></li>
 <?php
    }

}

?>

<div class="pager"></div>	
<?php
		} catch(Murmur_ServerBootedException $exc) {
			echo '<div class="error">Server is not running</div>';
		}
		}else{
            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $usersStr = $server->getRegisteredUserIds($entId);	
            $users = explode(",", $usersStr);
            if(count($users) > 1)
                array_pop($users);
            //array_shift($users);
            $i =count($users);
            echo json_encode($i);
        }
    }
        public static function server_get_location()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $userid = intval($_POST['uid']);
            $startTime = intval($_POST['startTime']);
            $endTime = intval($_POST['endTime']);

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $locations = $server->getLocation($entid, $userid, $startTime, $endTime);
            $locationArray = array();
            forEach($locations As $key => $location)
            {
                array_push($locationArray,$location);
            }
            echo json_encode($locationArray); 
        }
        public static function server_get_channel_locations()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $channelid = intval($_GET['cid']);

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $locations = $server->getChannelLocations($entid, $channelid);
            $points = split(";",$locations);
            $pointArray = array();
            foreach($points as $point)
            {
                $pointObj = array();
                if($point == "") break;
                $fields = split(",", $point);
                //if(count($fields) != 5)
                //    break;
                $pointObj["uid"]  = $fields[0];
                $pointObj["nick"] = $fields[1];
                $pointObj["lng"]  = $fields[2];
                $pointObj["lat"]  = $fields[3];
                array_push($pointArray, $pointObj);
            }
            echo json_encode($pointArray);
        }
        public static function server_set_channel_fence()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $channelid = intval($_POST['cid']);
            $startTime = intval($_POST['startTime']);
            $endTime = intval($_POST['endTime']);
            $fence = $_POST['fence'];

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $server->setChannelFence($entid, $channelid, $startTime, $endTime, $fence);
        }
        public static function server_get_videos()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $userid = 1001001;
            $startTime = 1495267561;
            $endTime = 1494676608;

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $videos = $server->getVideos($entid, $userid, $startTime, $endTime);
            var_dump($videos);
        }
        public static function server_get_video()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $id = 1;
            $saveName = "test.mp4";

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $video = $server->getVideo($id);
            HelperFunctions::binaryToFile($video, $saveName); 
        }

        public static function server_get_images()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $userid = 1001001;
            $startTime = 1495267562;
            $endTime = 1495280239;

            if ( SessionManager::getInstance()->getLoginId() != $entid)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $photos = $server->getPhotos($entid, $userid, $startTime, $endTime);
            var_dump($photos);
        }

        public static function server_get_image()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $id = 6;
            $saveName = "test1.jpg";

            if ( SessionManager::getInstance()->getLoginId() != $entid)
                return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $photo = $server->getPhoto($id);
            HelperFunctions::binaryToFile($photo, $saveName);
        }
        public static function server_add_friends()
        {
            $mt4Ids = !empty($_POST['mt4Ids']) ? $_POST['mt4Ids'] : false;
            $stripMt4Ids = preg_replace('/[\"\[\]]/', '', $mt4Ids);
            $mt4IdsToArr = explode(',', $stripMt4Ids);
            $serverId =$_GET['sid'];
            $userid =intval($_GET['uid']);
            $entId =intval($_GET['entId']);

            if ( SessionManager::getInstance()->getLoginId() != $entId)
		    return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $res = $server->addFriends($entId, $userid, $stripMt4Ids);

            $data = array(
                    'state' => 1,
                    'msg'  => '操作成功'
                    );
            echo json_encode($data);
        }
        
        public static function server_get_friends()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $userid = 1001001;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $res = $server->getFriends($entid, $userid);
            var_dump($res);
        }

        public static function server_friend_remove()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $userId = intval($_POST['uid']);
            $friendId = $_POST['fid'];

            if ( SessionManager::getInstance()->getLoginId() != $entid)
                return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $res = $server->deleteFriends($entid, $userId, $friendId);
            var_dump($res);
        }
        
        public static function server_send_message()
        {
            $serverId = 1;
            $entid = intval($_GET['entId']);
            $cid = intval($_POST['cid']);
            $info = $_POST['message'];
            if ( SessionManager::getInstance()->getLoginId() != $entid)
                return ;

            $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
            $res = $server->sendMessageToChannel($entid, $cid, $info);
            var_dump($res);
        }
	public static function server_regstration_remove()
	{
		$sid = intval($_POST['sid']);
		$entId = intval($_GET['entId']);
		$uid = intval($_POST['uid']);
        if ( SessionManager::getInstance()->getLoginId() != $entId)
            return ;

        ServerInterface::getInstance()->removeRegistration($sid,$entId, $uid);
        $data = array(
                'state' => 1,
                'msg'  => '操作成功'
                );
        echo json_encode($data);
    }
	
	public static function server_channel_remove()
	{
        $sid = intval($_POST['sid']);
        $entId = intval($_GET['entId']);
        $cid = intval($_POST['aid']);

        if ( SessionManager::getInstance()->getLoginId() != $entId)
            return ;

        ServerInterface::getInstance()->removeChannels($sid,$entId,$cid);
    }
	public static function server_member_remove()
    {
		$srid =intval($_POST['sid']);
        $entId = intval($_GET['entId']);
		$cid =intval($_POST['cid']);
		$uid =$_POST['uid'];

        if ( SessionManager::getInstance()->getLoginId() != $entId)
            return ;
		$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
        $server->deleteChannelMembers($entId,$cid, $uid);
	}
	
	public static function server_channel_add()
	{
		$sid = intval($_POST['sid']);
		$entId = intval($_GET['entId']);
		$name =trim($_POST['name']);
	
        if ( SessionManager::getInstance()->getLoginId() != $entId)
                return ;

		ServerInterface::getInstance()->addChannels($sid,$entId,$name);
	}
	
	public static function server_channel_save()
	{
		$sid = intval($_POST['sid']);
        $entId = intval($_GET['entId']);
		$cid =intval($_POST['cid']);
		$name =trim($_POST['name']);

        if ( SessionManager::getInstance()->getLoginId() != $entId)
                return ;

        ServerInterface::getInstance()->updateChannelName($sid,$entId, $cid,$name);
	}
	public static function server_regstration_genpw()
	{
		
		$serverId = intval($_POST['serverId']);
		$userId = intval($_POST['userId']);
		$newPw = $_POST['newPw'];
	
        ServerInterface::getInstance()->updateUserPw($serverId, $userId, $newPw);
	
        $data = array(
		   'state' => 1,
		   'msg'  => '操作成功'
		);
		echo json_encode($data);
		return false;
	}
    public static function show_tree_en()
	{
		$sid = intval($_POST['sid']);
		$entId = intval($_GET['entId']);

        if ( SessionManager::getInstance()->getLoginId() != $entId)
                return ;


		if(!isset($_GET['najax'])){
            $pageIndex =intval($_POST['pageIndex'])-1;
            $pageSize =intval($_POST['pageSize']);
            $curpage = $pageIndex*$pageSize;
            $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels($entId);
            unset($data[0]);//不能使用array_shift()这样会重置数组索引
            $data =array_slice($data,$curpage,$pageSize,true);

	 ?>
	 

	 <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     
	 <tr>
     <th width="120" align="left">Channel ID</th>
     <th align="left">Channel Name</th>
     <th align="left">Members</th>    
     <th width="80" align="center">Operation</th>
     </tr>

	 <?php
		 foreach($data as $k =>$row){
             // echo "<pre>";
             // echo $k;
             $num =explode(",",$row->members);
             //var_dump($num);
             if(count($num) >= 1) array_pop($num);
             $n =count($num)>0?count($num):0;
             //echo $n;
             //if($k==0) continue;

		 ?>
		<tr>
       <td align='left'><?php echo $k; ?></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $n;?><a href='?page=user&sid=1&action=show_members&cid=<?php echo $row->id; ?>'> &nbsp;Detail</a> | <a href='?page=channelmap&sid=1&action=show_members&cid=<?php echo $row->id; ?>'> &nbsp;Map</a></td>
		<td align="center"><a href="./?page=channel&action=edit&sid=1&cid=<?php echo $row->id; ?>">Edit</a> | <a href="javascript:;" onclick="window.wxc.xcConfirm('Are you sure to remove?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_server_channel_remove(<?php echo $row->id; ?>);}})">Remove</a></td>
     </tr>
	 <?php
      // ++$i;
		 }
		?>
		
		
		</table>

<?php
		}else{
			
            $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels($entId);
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
	public static function show_tree()
	{
		$sid = intval($_POST['sid']);
		$entId = intval($_GET['entId']);

        if ( SessionManager::getInstance()->getLoginId() != $entId)
                return ;


		if(!isset($_GET['najax'])){
            $pageIndex =intval($_POST['pageIndex'])-1;
            $pageSize =intval($_POST['pageSize']);
            $curpage = $pageIndex*$pageSize;
            $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels($entId);
            unset($data[0]);//不能使用array_shift()这样会重置数组索引
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
             $num =explode(",",$row->members);
             //var_dump($num);
             if(count($num) >= 1) array_pop($num);
             $n =count($num)>0?count($num):0;
             //echo $n;
             //if($k==0) continue;

		 ?>
		<tr>
       <td align='left'><?php echo $k; ?></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $n;?><a href='?page=user&sid=1&action=show_members&cid=<?php echo $row->id; ?>'> &nbsp;查看</a> | <a href='?page=channelmap&sid=1&action=show_members&cid=<?php echo $row->id; ?>'> &nbsp;查看地图</a></td>
		<td align="center"><a href="./?page=channel&action=edit&sid=1&cid=<?php echo $row->id; ?>">编辑</a> | <a href="javascript:;" onclick="window.wxc.xcConfirm('确定删除吗?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_server_channel_remove(<?php echo $row->id; ?>);}})">删除</a></td>
     </tr>
	 <?php
      // ++$i;
		 }
		?>
		
		
		</table>

<?php
		}else{
			
            $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels($entId);
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
	  
    public static function server_enterprise_add(){
        $sid =$_GET['sid'];
        $parentId =$_GET['uid'];
        $uname=$_POST['uname'];
        $passwd=$_POST['passwd'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $comment=$_POST['comment'];
        $previlege='r,w';
        $type=2;
        $res;

        try {
            $res = MysqlInterface::addEnterprise($parentId,$type,$uname,$passwd,$email,$phone,$comment,$previlege);
        } catch(Exception $exc) {

        }
        if ($res > 0)
            echo "succeed!";
    }
    public static function server_enterprise_update(){
        $id =$_GET['id'];
        $passwd=$_POST['passwd'];
        if ($passwd == '')
            $passwd = null;
        $name=$_POST['name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $comment=$_POST['comment'];
        $res;
        //if (!PermissionManager::getInstance()->serverCanEditAdmins())
        //        return ;
        try {

            $res = MysqlInterface::updateEnterprise($id,null,$passwd, $name,$email,$phone,$comment,null);

        } catch(Exception $exc) {

        }
        if ($res > 0)
            echo "succeed!";
    }
    public static function server_enterprise_remove(){
        $id =$_GET['id'];
        $res;
        //if (!PermissionManager::getInstance()->serverCanEditAdmins())
        //        return ;
        try {
            $res = MysqlInterface::deleteEnterpriseById($id);
        } catch(Exception $exc) {

        }
        if ($res > 0)
            echo "succeed!";
    }
	public static function server_getEnterprises_en()
	{
        if(!isset($_GET['najax'])){
            try {
                $pageIndex =intval($_POST['pageIndex'])-1;
                $pageSize =intval($_POST['pageSize']);
                $curpage = $pageIndex*$pageSize;
                $users = MysqlInterface::getEnterprises();

                $users =array_slice($users,$curpage,$pageSize,true);
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">User ID</th>
      <th width="20" align="center">Name</th>
      <th width="60" align="center">Email</th>
      <th width="60" align="center">Telephone</th>
      <th width="60" align="center">Comment</th>
      <th width="80" align="center">Operation</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>


      <td align="center">
             <a href="./?page=enterprise&sid=1&action=edit&id=<?php echo $member['id'] ?>" >Edit</a> | <a href="javascript:;" onclick="if(window.wxc.xcConfirm('Are you sure to remove?',window.wxc.xcConfirm.typeEnum.warning)){jq_enterprise_remove(<?php echo $member['id'] ?>);}">Remove</a>
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
            $users = MysqlInterface::getEnterprises();	
            $i =count($users);
            echo json_encode($i);
        } 		
    }
    public static function server_enterprise_search_en()
    {
        $value =addslashes($_POST['value']);
        $type =intval($_POST['type']);
        $users = MysqlInterface::searchEnterprises($type, $value);
        $total = count($users);
?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">User ID</th>
      <th width="20" align="center">Account</th>
      <th width="60" align="center">Email</th>
      <th width="60" align="center">Telephone</th>
      <th width="60" align="center">Comment</th>
      <th width="80" align="center">Operation</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>


      <td align="center">
             <a href="./?page=enterprise&sid=1&action=edit&id=<?php echo $member['id'] ?>" >Edit</a> | <a href="javascript:;" onclick="if(window.wxc.xcConfirm('Are you sure to remove?',window.wxc.xcConfirm.typeEnum.warning)){jq_enterprise_remove(<?php echo $member['id'] ?>);}">Remove</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "Total ".$total." items，totally 1 page，current page 1"?></div>	

<?php               
        }
	public static function server_getEnterprises()
	{
        if(!isset($_GET['najax'])){
            try {
                $pageIndex =intval($_POST['pageIndex'])-1;
                $pageSize =intval($_POST['pageSize']);
                $curpage = $pageIndex*$pageSize;
                $users = MysqlInterface::getEnterprises();

                $users =array_slice($users,$curpage,$pageSize,true);
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="60" align="center">备注</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>


      <td align="center">
             <a href="./?page=enterprise&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;" onclick="if(window.wxc.xcConfirm('确定删除用户?',window.wxc.xcConfirm.typeEnum.warning)){jq_enterprise_remove(<?php echo $member['id'] ?>);}">删除</a>
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
            $users = MysqlInterface::getEnterprises();	
            $i =count($users);
            echo json_encode($i);
        } 		
    }
    public static function server_enterprise_search()
    {
        $value =addslashes($_POST['value']);
        $type =intval($_POST['type']);
        $users = MysqlInterface::searchEnterprises($type, $value);
        $total = count($users);
?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="60" align="center">备注</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>


      <td align="center">
             <a href="./?page=enterprise&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;" onclick="if(window.wxc.xcConfirm('确定删除用户?',window.wxc.xcConfirm.typeEnum.warning)){jq_enterprise_remove(<?php echo $member['id'] ?>);}">删除</a>
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

        public static function batch_add_enterprise()
        {
            $res = array();

            if ($_FILES["fileToUpload"]["error"] > 0)
            {
                $res['code']  = 400;
                $res['reason'] = $_FILES["fileToUpload"]["error"];
                echo json_encode($res);
                return;
            }
            $parentId = $_GET['uid'];
            $file_types = explode ( ".", $_FILES ['fileToUpload']['name'] );
            $file_type = $file_types[count ($file_types) - 1];
            $SUPPORT_TYPE = array('csv','xls','xlsx');
            if (!in_array($file_type,$SUPPORT_TYPE))
            {
                $res['code']  = 400;
                $res['reason'] = "unsupport document type.";
                echo json_encode($res);
                return;
            }
            $PHPExcel=null;
            if ($file_type == 'xls')
            {
                $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
                $PHPExcel = $reader->load($_FILES["fileToUpload"]["tmp_name"]); // 载入excel文件
            }else if ($file_type == 'xlsx')
            {
                $PHPExcel = PHPExcel_IOFactory::load($_FILES["fileToUpload"]["tmp_name"]);
            }
            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            $code = 200;
            $reason = '';
            /** 循环读取每个单元格的数据 */
            for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
                $name = $sheet->getCell("A".$row)->getValue();
                $passwd = $sheet->getCell("B".$row)->getValue();
                if ($name == '' || $passwd == '')    continue;
                $email = $sheet->getCell("C".$row)->getValue();
                $phone = $sheet->getCell("D".$row)->getValue();
                $comment = $sheet->getCell("E".$row)->getValue();
                $previlege='r,w';
                $type=2;

                $result = MysqlInterface::addEnterprise($parentId,$type,$name,$passwd,$email,$phone,$comment,$previlege);
                if ($result <= 0)
                {
                    $code = 400;
                    $reason = $reason." | 文件行 ".$row." 添加失败";
                }
            }
            $res['code'] = $code;
            $res['reason'] = $reason;
            echo json_encode($res);
        }

        public static function server_enterprise_file_output()
        {
		    $value =addslashes($_POST['value']);
            $type =intval($_POST['type']);
            $users = MysqlInterface::searchEnterprises($type, $value);                 
            $total = count($users);

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

            foreach($users as $row)
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
            $filename = "Enterprise";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$filename.'.csv');
            header('Cache-Control: max-age=0');
            exit;
        }
        public static function server_getRecordsByEnterprise_en()
        {
            if(!isset($_GET['najax'])){
                try {
                    $pageIndex =intval($_POST['pageIndex'])-1;
                    $pageSize =intval($_POST['pageSize']);
                    $enterpriseId =intval($_GET['enterpriseId']);
                    $curpage = $pageIndex*$pageSize;
                    $records = MysqlInterface::getRecordsByEnterprise($enterpriseId);
                    $records =array_slice($records,$curpage,$pageSize,true);
?>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">Record ID</th>
      <th width="40" align="center">Agency ID</th>
      <th width="30" align="center">Name</th>
      <th width="30" align="center">Normal Cards</th>
      <th width="30" align="center">Permanent Cards</th>
      <th width="30" align="center">Money</th>
      <th width="60" align="center">Operate Time</th>
     </tr>
<?php
            foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['fromId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['pCardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


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
                $enterpriseId =intval($_GET['enterpriseId']);
                $records = MysqlInterface::getRecordsByEnterprise($enterpriseId);
                $i =count($records);
                echo json_encode($i);
            } 		
        }
        public static function server_record_searchByEnterprise_en()
        {
            $value =addslashes($_POST['value']);
            $type =intval($_POST['type']);
            $enterpriseId = intval($_GET['enterpriseId']);
            $startTime = $_POST['startTime'];
            $endTime = $_POST['endTime'];
            $records = MysqlInterface::searchRecordsEnterpriseFromOperator($enterpriseId, $type, $value, $startTime, $endTime);
            $total = count($records);
?>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">Record ID</th>
      <th width="40" align="center">Agency ID</th>
      <th width="30" align="center">Name</th>
      <th width="30" align="center">Normal Cards</th>
      <th width="30" align="center">Permanent Cards</th>
      <th width="30" align="center">Money</th>
      <th width="60" align="center">Operate Time</th>
     </tr>
<?php
            foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['fromId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['pCardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


     </tr>

<?php
            }
?>

            </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "Total ".$total." items，totally 1 page，current page 1"?></div>	

<?php               
        
        }
      	public static function server_getRecordsByEnterprise()
        {
            if(!isset($_GET['najax'])){
                try {
                    $pageIndex =intval($_POST['pageIndex'])-1;
                    $pageSize =intval($_POST['pageSize']);
                    $enterpriseId =intval($_GET['enterpriseId']);
                    $curpage = $pageIndex*$pageSize;
                    $records = MysqlInterface::getRecordsByEnterprise($enterpriseId);
                    $records =array_slice($records,$curpage,$pageSize,true);
?>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">记录号</th>
      <th width="40" align="center">代理商编号</th>
      <th width="30" align="center">联系人</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">永久卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
     </tr>
<?php
            foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['fromId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['pCardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


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
                $enterpriseId =intval($_GET['enterpriseId']);
                $records = MysqlInterface::getRecordsByEnterprise($enterpriseId);
                $i =count($records);
                echo json_encode($i);
            } 		
        }
        public static function server_record_searchByEnterprise()
        {
            $value =addslashes($_POST['value']);
            $type =intval($_POST['type']);
            $enterpriseId = intval($_GET['enterpriseId']);
            $startTime = $_POST['startTime'];
            $endTime = $_POST['endTime'];
            $records = MysqlInterface::searchRecordsEnterpriseFromOperator($enterpriseId, $type, $value, $startTime, $endTime);
            $total = count($records);
?>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">记录号</th>
      <th width="40" align="center">代理商编号</th>
      <th width="30" align="center">联系人</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">永久卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
     </tr>
<?php
            foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['fromId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['pCardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


     </tr>

<?php
            }
?>

            </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               
        
        }

}

