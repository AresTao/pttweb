<script>
	


</script>

<?php
if($_POST['action']=='lists'){
	
	
	
}
?>
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
    <li class="noRight"><a href="?page=logout">退出</a></li>
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
 <?php
   if(isset($_GET['action'])&&$_GET['action']=='show_friends'){
   ?>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>好友列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div>  <a href="./?page=friends&sid=1&action=friend_add&uid=<?php echo intval($_GET['uid']) ?>" class="actionBtn add">添加好友</a> 好友列表</h3>

<?php
$id = SessionManager::getInstance()->getLoginId();
$srid =$_GET['sid'];
$uid = intval($_GET['uid']);
$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
$friendsStr =$server->getFriends(intval($id), $uid);

//$chs =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']))->getChannels($_GET['sid'],$_GET['cid']);

$members =explode(",",$friendsStr);
//var_dump($members);die;
?>

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="150">用户名</th>
	  <th width="150">昵称</th>
      

      <th width="80" align="center">操作</th>
     </tr>
	 
<?php

foreach($members as $member){
        if($member == "") continue;
	$mr=intval($member);
	/*$usersStr = $server->getRegisteredUserIds(intval($id));
        $users = explode(",", $usersStr);
        if(count($users) > 1)
            array_pop($users);
        
        var_dump($users);
        var_dump($mr);*/
//if(array_key_exists($mr,$users)){
        
	$user = ServerInterface::getInstance()->getServerRegistration($srid,$id, $mr);
	if($user->getUserId()!==0){
?>
	 
          <tr>
    
      <td align="center"><?php echo $user->getUserId();?></td>
      <td><?php echo $user->getName();?></td>
      <td align="center"><?php echo $user->getEmail();?></td>
	  

      <td align="center">
             <a href="javascript:;" onclick="window.wxc.xcConfirm('确定删除好友?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_friend_remove(<?php echo $user->getUserId() ?>,<?php echo $uid?>);}})">删除</a>
             </td>
     </tr>



<?php
	}
//}
		
?>
<?php

		
//	}
	//}
}
?>







         </table>



    <div class="clear"></div>
	<!--
    <div class="pager">总计  个记录，共 1 页，当前第 1 页 | <a href="article.php?page=1">第一页</a> 上一页 下一页 <a href="article.php?page=1">最末页</a></div>           </div>-->

   <!-- 当前位置 -->
   <?php
  } 
  if(isset($_GET['action'])&&$_GET['action']=='friend_add'){
	                $id = SessionManager::getInstance()->getLoginId(); 
                        $uid=intval($_GET['uid']);
	  		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']));
			$friendsStr =$server->getFriends(intval($id), $uid);


			$mbs =explode(",",$friendsStr);
			
			$usersStr = $server->getRegisteredUserIds(intval($id), "");
                        $users = explode(",",$usersStr);
                        if(count($users) > 1)
                            array_pop($users);
		
   ?>
<div id="urHere">管理中心<b>></b><strong>好友管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=friends&sid=1&action=show_friends&uid=<?php echo $_GET['uid'];?>" class="actionBtn">返回列表</a>添加好友</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">用户名</td>
	<td width="98%">
	   <?php
	   $i=0;
	   foreach ($users as $key):
	   
	  /* echo $key;
	   echo "<pre>";
	   var_dump($mbs);*/
  if($key==0 || in_array($key,$mbs) || intval($key) == $uid)continue;
	  
	   if($i!==0 &&$i%5==0)echo "<br>";
	   ?>
	    
        <label style="float:left;width:150px;" for="uname<?php echo $key;?>"><input type="checkbox" name="uid"  class="inpMain" id="uname<?php echo $key;?>"   value="<?php echo $key;?>"/><?php echo $key?></label>
		
		<?php
		++$i;
		endforeach;
		?>
		<input type="hidden" name="uid" value="<?php echo $_GET['uid'];?>">
      </td>
      </tr>

      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_friend_add(<?php echo $_GET['uid'];?>)" />
       </td>
      </tr>
     </table>
    </form>
	<table>
		<tr><td ></td><td><input type="checkbox" id="selectAll" ><label for="selectAll">全选/全不选</label></td></tr>
	</table>
		<div class="message"></div>
		
	<script>
	$(function(){
		
		$("#selectAll").click(function(){    
    if(this.checked){    
        $(".tableBasic :checkbox").attr("checked", true);   
    }else{    
        $(".tableBasic :checkbox").attr("checked", false); 
    }    
});
		
	})
	</script>
                   </div>
   <?php
   }
   if(!isset($_GET['action'])){
   ?>
<script>
$(function(){
	
	$('#pldr').click(function(e){
		
		e.preventDefault();
		$("input[type=file]").trigger('click');
   
		
	})
	$("input[type=file]").change(function(){
		
		alert('提交成功');
	});
	$("#catlist").change(function(){
		var val = $(this).val();
		$.post(
		"./?ajax=server_change&sid=1",
		{'v':val},
		function(data){
			
			$("#list").find("table").remove();
			$("#list").html(data);
				$(".pager").html("总计"+total+"个记录，共 1 页，当前第 1 页 </div> ");
		
		}
		
		)
		
	})
	
})

</script>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>用户列表</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" id="pldr" class="actionBtn add">批量导入</a> <div style="width:10px;"></div>  <a href="./?page=user&sid=1&action=add" class="actionBtn add">新建用户</a> 用户列表</h3>
<form action="?page=user&action='lists'" method="post" enctype="multipart/form-data">	
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="short-filter">
    <form action="" method="post">
     <div class="item-left">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>搜索类型</label>
     <select name="cat_id" id="catlist">
      <option value="0">全部</option>
                  <option value="1">在线用户 </option>
                        <option value="2"> 离线用户</option>

						
                 </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div>
     <div class="btn-item">
     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_server_search();return false;" />
     </div>
    </form>

    </div>
        <div id="list">


    </div>
    <div class="clear"></div>
	

    <div class="pager"></div>          
	</div>
</div>
<?php
   }
  			   
	?>

	</div>



	<hr/>

	<div id="jq_information"></div>

	<script type="text/javascript">
	
                        function jq_friend_remove(fid,uid)
			{
				$.post(
							"./?ajax=server_friend_remove&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid,'fid':fid},
							function(data) {
								if (data.length>0) {
                                                                        window.wxc.xcConfirm("删除成功.", window.wxc.xcConfirm.typeEnum.success);
									location.href="?page=friends&sid=1&action=show_friends&uid="+uid;
								}else{
									location.href="?page=user&sid=1&action=show_members&cid="+cid;
									
									
								}
								
							}
				);
			}
                       
			
                        function jq_friend_add(uid)
			{
		            mt4Ids=[];
		            $('input[name=uid]').each(function() {
			        if(this.checked) {
			        mt4Ids.push($(this).val());
			    }
			    });
			    data = {
				mt4Ids : JSON.stringify(mt4Ids)
			    };
			
			    $.post('./?ajax=server_add_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>&uid='+uid, data, function(data){
				if(data.state == 1){
			                window.wxc.xcConfirm("添加成功.", window.wxc.xcConfirm.typeEnum.success);	
					location.href = "?page=user&sid=1&action=show_members&cid="+cid;
				}else{
					alert("操作失败");
				}
			}, 'json');	

			}	
		
                        function jq_get_location(){
                                $.post(
                                "./?ajax=server_get_location&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_videos(){
                                $.post(
                                "./?ajax=server_get_videos&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_video(){
                                $.post(
                                "./?ajax=server_get_video&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }

                        function jq_get_images(){
                                $.post(
                                "./?ajax=server_get_images&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_image(){
                                $.post(
                                "./?ajax=server_get_image&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_add_friends(){
                                $.post(
                                "./?ajax=server_add_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_friends(){
                                $.post(
                                "./?ajax=server_get_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_send_message(){
                                $.post(
                                "./?ajax=server_send_message&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
	</script>
