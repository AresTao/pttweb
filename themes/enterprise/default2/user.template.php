<script>
	
	var num;
		$(function(){
			

      $.ajax
       ({
           cache: false,
           async: false,
           type: 'post',
           data: { sid: "1" },
		   dataType: "json",
   url: "./?ajax=server_getRegistrations&page=user&najax=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
   success: function (data) { 
			num =data;
			
   }
});


	
	})

	 var pageIndex = 0;     //页面索引初始值   
		 var pageSize=20;
		
	 
 $(function () {
		
      InitTable(0);    //Load事件，初始化表格数据，页面索引为0（第一页）
	
		
	//分页，PageCount是总条目数，这是必选参数，其它参数都是可选
	$(".pager").pagination(num, {
	    callback: PageCallback,  //PageCallback() 为翻页调用次函数。
	    prev_text: "« previous",
	    next_text: "next »",
	    items_per_page:pageSize,
	    num_edge_entries: 2,       //两侧首尾分页条目数
	    num_display_entries: 6,    //连续分页主体部分分页条目数
	    current_page: pageIndex,   //当前页索引
				load_first_page:true
	});
	//翻页调用   
	function PageCallback(index, jq) {             
	    InitTable(index); 
				if(num<20){
					
					$(".pager").html("Total "+num+" items，totally 1 page，current page 1 </div> ");
				}					
	}  
	//请求数据   
	function InitTable(pageIndex) {                                  
	    $.ajax({   
		type: "POST",  
		url: './?ajax=server_getRegistrations&page=user&entId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
                        data: "pageIndex=" + (pageIndex+1) + "&pageSize=" + pageSize+"&sid=1",          //提交两个参数：pageIndex(页面索引)，pageSize(显示条数)                   
                        success: function(data) {
                             //移除Id为Result的表格里的行，从第二行开始（这里根据页面布局不同页变）   
							
                            $("#list").html(data);             //将返回的数据追加到表格   
						
							
                        }  
                    }); 
                }
            }); 

		
function uploadFile(obj,type){
$.ajaxFileUpload	
}

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
    <li class="noRight"><a href="#"> Enterprise ID：<?php echo $enterprise['id'];?></a></li>
        <li class="noRight"><a href="#"> Enterprise Name：<?php echo $enterprise['name'];?> </a> </li>
   </ul>
   <ul class="navRight">
        <li class="noLeft"><a href="#">Permanent Cards：<?php echo $enterprise['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">Normal Year Cards：<?php echo $enterprise['availableCards'];?></a></li>
    <li class="M noLeft"><a href="JavaScript:void(0);">Hi，<?php echo SessionManager::getInstance()->getLoginName();?></a>
     <div class="drop mUser">
      <a href="?page=admins&sid=1">Edit Info</a>
     </div>
    </li>
    <li class="noRight"><a href="?page=logout">Logout</a></li>
   </ul>
  </div>
 </div>
</div>
<!-- dcHead 结束 --> <div id="dcLeft"><div id="menu">

  <ul>
  <li><a href="?page=user&sid=1"><i class="user"></i><em>User Manage</em></a></li>
  <li><a href="?page=server&sid=1"><i class="mobile"></i><em>Channel Manage</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="article"></i><em>Monitor</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>Record</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">
 <?php
   if(isset($_GET['action'])&&$_GET['action']=='show_members'){
   ?>
<div id="urHere">Admin<b>></b><strong>Member List</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div>  <a href="./?page=user&sid=1&action=member_add&cid=<?php echo $_GET['cid'] ?>" class="actionBtn add">Add Member</a><a href="?page=server&sid=1" class="actionBtn">Back To List</a> Member List</h3>

<?php
$id = SessionManager::getInstance()->getLoginId();
$srid =$_GET['sid'];
$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
$chs =$server->getChannelState($id, $_GET['cid']);

//$chs =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']))->getChannels($_GET['sid'],$_GET['cid']);

$members =explode(",",$chs->getMembers());
//var_dump($members);die;
?>

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">User ID</th>
      <th width="100">Account</th>
	  <th width="100">Nick</th>
	  <th width="100">Priority</th>
      

      <th width="80" align="center">Operation</th>
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
            $memberLevel = $server->getChannelMemberLevel(intval($id),intval($_GET['cid']), $mr);
?>
	 
          <tr>
    
      <td align="center"><?php echo $user->getUserId();?></td>
      <td align="center"><?php echo $user->getAccount();?></td>
      <td align="center"><?php echo $user->getName();?></td>
      <td align="center"><?php if($memberLevel == 0){
      ?>
      <select name="memberLevel" id="memberLevel<?php echo $user->getUserId();?>">
                  <option value="0" selected="selected">Low</option>
                  <option value="1">Mid</option>
                  <option value="2">High</option>
     </select>
     <?php 
           }
           else if ($memberLevel == 1){
     ?>
     <select name="memberLevel" id="memberLevel<?php echo $user->getUserId();?>">
                  <option value="0">Low</option>
                  <option value="1" selected="selected">Mid</option>
                  <option value="2">High</option>
     </select>
     <?php 
           }
           else if ($memberLevel == 2){
           ?>
     <select name="memberLevel" id="memberLevel<?php echo $user->getUserId();?>">
                  <option value="0">Low</option>
                  <option value="1">Mid</option>
                  <option value="2" selected="selected">High</option>
     </select>
     <?php 
           }
     ?>
</td>
	  

      <td align="center">
            <a href="javascript:;" onclick="jq_set_member_level(<?php echo intval($_GET['cid'])?>, <?php echo $user->getUserId() ?>);">Set Priority</a> | <a href="javascript:;" onclick="window.wxc.xcConfirm('Are you sure to remove this user ?',window.wxc.xcConfirm.typeEnum.warning,{onOk:function(){jq_server_member_remove(<?php echo $user->getUserId() ?>,<?php echo intval($_GET['cid'])?>);}})">Remove</a>
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
  if(isset($_GET['action'])&&$_GET['action']=='member_add'){
	                $id = SessionManager::getInstance()->getLoginId(); 
	  		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']));
			
			$chs =$server->getChannelState($id, $_GET['cid']);
			$members =$chs->getMembers();
			$mbs = explode(",",$members);
			
			$usersStr = $server->getRegisteredUserIds(intval($id), "");
                        $users = explode(",",$usersStr);
                        if(count($users) > 1)
                            array_pop($users);
		
   ?>
<div id="urHere">Admin<b>></b><strong>Member Manage</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1&action=show_members&cid=<?php echo $_GET['cid'];?>" class="actionBtn">Back To List</a>Add Member</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">User Name</td>
	<td width="98%">
	   <?php
	   $i=0;
	   foreach ($users as $key):
	   
	  /* echo $key;
	   echo "<pre>";
	   var_dump($mbs);*/
  if($key==0 || in_array($key,$mbs))continue;
	  
	   if($i!==0 &&$i%5==0)echo "<br>";
	   ?>
	    
        <label style="float:left;width:150px;" for="uname<?php echo $key;?>"><input type="checkbox" name="uid"  class="inpMain" id="uname<?php echo $key;?>"   value="<?php echo $key;?>"/><?php echo $key?></label>
		
		<?php
		++$i;
		endforeach;
		?>
		<input type="hidden" name="cid" value="<?php echo $_GET['cid'];?>">
      </td>
      </tr>

      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="Submit" onclick="jq_member_add(<?php echo $_GET['cid'];?>)" />
       </td>
      </tr>
     </table>
    </form>
	<table>
		<tr><td ></td><td><input type="checkbox" id="selectAll" ><label for="selectAll">SelectAll/UnSelectAll</label></td></tr>
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
		
		alert('Submit success.');
	});
	$("#catlist").change(function(){
		var val = $(this).val();
		$.post(
		"./?ajax=server_change&sid=1",
		{'v':val},
		function(data){
			
			$("#list").find("table").remove();
			$("#list").html(data);
				$(".pager").html("Total "+total+" items，totally 1 page，current page 1 </div> ");
		
		}
		
		)
		
	})
	
})

</script>
<div id="urHere">Admin<b>></b><strong>User List</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" id="pldr" class="actionBtn add">Batch In</a> <div style="width:10px;"></div>  <a href="./?page=user&sid=1&action=add" class="actionBtn add">Add User</a> User List</h3>
<form action="?page=user&action='lists'" method="post" enctype="multipart/form-data">	
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="short-filter" style="display:none;">
    <form action="" method="post" style="display:none;">
     <div class="item-left">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>Search Type</label>
     <select name="cat_id" id="catlist">
      <option value="0">All</option>
                  <option value="1">Online </option>
                        <option value="2">Offline</option>

						
                 </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div>
     <div class="btn-item">
     <input name="submit" class="btnGray" type="submit" value="Search" onclick="jq_server_search();return false;" />
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
  if(isset($_GET['action']) && $_GET['action']=='add'){
	  
	  
	  

?>
<div id="urHere">Admin<b>></b><strong>User Manage</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1" class="actionBtn">Back To List</a>Add User</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr> 
      <td width="100" align="right">User Type</td>
      <td>            
             <select name="type" id="type">
                  <option value="1">Normal Year Card </option>
                  <option value="0">Permanent Card</option>
             </select>
      </td>
      </tr>
      <tr>
       <td width="100" align="right">Account</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value=""/>
       </td>
      </tr>
      <tr>
       <td align="right">Password</td>
       <td>
        <input type="password" name="pwd" size="40" class="inpMain" id="upwd" value="" />
       </td>
      </tr>
      <tr>
       <td align="right">Confirm Password</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" id="rpwd" />
       </td>
      </tr>
      <tr>
       <td align="right">Nick</td>
       <td>
        <input type="text" name="nick" size="40" class="inpMain" id="" />
       </td>
      </tr>
      <tr>
      <tr>
       <td align="right">Comment</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="" />
       </td>
      </tr>
      <tr>
       <td align="right">Email</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="" />
       </td>
      </tr>
      <tr>
       <td align="right">Telephone</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="" />
       </td>
      </tr>
      <tr> 
      <td width="100" align="right">Alarm</td>
      <td>            
             <select name="fenceAlarm" id="fenceAlarm">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
             </select>
      </td>
      </tr>


      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="Submit" onclick="jq_user_add()" />
       </td>
      </tr>
     </table>
    </form>
		<div class="message"></div>
                   </div>
				   
				   
	<?php
	}
 if(isset($_GET['action']) && $_GET['action']=='edit'){
	
	$uid =intval($_GET['uid']);
	$sid=intval($_GET['sid']);
        $entId=SessionManager::getInstance()->getLoginId();	
	$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid));
	$user = $server->getRegistration($entId, $uid);
	//var_dump($user);die;
		
	  

?>
<div id="urHere">Admin<b>></b><strong>User Manage</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1" class="actionBtn">Back To List</a>Edit User Info</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">Account</td>
       <td>
        <input type="hidden" name="uid" size="40" class="inpMain" id="uid"  value='<?php echo $uid;?>'/>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value='<?php echo $user->getAccount();?>'/>
       </td>
      </tr>
      <tr>
       <td align="right">Password</td>
       <td>
        <input type="password" name="pwd" size="40" class="inpMain" id="upwd" value="123456" />
       </td>
      </tr>
      <tr>
       <td align="right">Nick</td>
       <td>
        <input type="text" name="nick" size="40" class="inpMain" id="" value='<?php echo $user->getName();?>'/>
       </td>
      </tr>
      <tr>
      <tr>
       <td align="right">Comment</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="" value='<?php echo $user->getComment();?>'/>
       </td>
      </tr>
      <tr>
       <td align="right">Email</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id=""  value='<?php echo $user->getEmail();?>'/>
       </td>
      </tr>
      <tr>
       <td align="right">Telephone</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id=""  value='<?php echo $user->getPhone();?>'/>
       </td>
      </tr>
      <tr> 
      <td align="right">Alarm</td>
      <td>            
             <select name="fenceAlarm" id="fenceAlarm">
             <?php if($user->getFenceAlarm() == "1"){ ?>
                  <option value="0" >No</option>
                  <option value="1" selected="selected">Yes</option>
             <?php }else{?>
                 <option value="0" selected="selected">No</option>
                  <option value="1">Yes</option>
             <?php }?>
             </select>
      </td>
      </tr>




      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="Submit" onclick="jq_user_update()" />
       </td>
      </tr>
     </table>
    </form>
		<div class="message"></div>
                   </div>
				   
				   
	<?php
	}
        if(isset($_GET['action']) && $_GET['action']=='renew'){
            $uid =intval($_GET['uid']);
	    $sid=intval($_GET['sid']);
	    $entId=SessionManager::getInstance()->getLoginId();
	    $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid));
	    $user = $server->getRegistration($entId, $uid);
 
	?>
<div id="urHere">Admin<b>></b><strong>User Manage</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1" class="actionBtn">Back To List</a>Renew User</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     
      <tr>
       <td width="100" align="right">User ID</td>
       <td>
        <input type="text" name="uid" size="40" readonly="true" class="inpMain" id="uid"  value='<?php echo $uid;?>'/>
       </td>
      </tr>
       <tr>
       <td width="100" align="right">User Account</td>
       <td>
        <input type="text" name="uname" size="40" readonly="true" class="inpMain" id="uname"  value='<?php echo $user->getAccount();?>'/>
       </td>
      </tr>
      
      <tr>
      <td width="100" align="right">Renew Type</td>
      <td>
             <select name="type" id="type">
                  <option value="0">Become Permanent</option>
                  <option value="1">Renew Year Card </option>
             </select>
      </td>
      </tr> 

      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="Submit" onclick="jq_user_renew()" />
       </td>
      </tr>
     </table>
    </form>
		<div class="message"></div>
                   </div>
<?php
}
?>	
	</div>



	<hr/>

	<div id="jq_information"></div>

	<script type="text/javascript">
	
			function jq_server_setSuperuserPassword(sid)
			{
				var pw = '123456';
				sid=1;
				$.post('./?ajax=server_setSuperuserPassword',
						{ 'sid':sid , 'pw': pw },
						function (data) {
							if (data=='') {
								$('#li_server_superuserpassword ').html('<div>Password set to: '+pw+'</div>');
							} else {
								$('#li_server_superuserpassword').html(data);
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
                                                                        window.wxc.xcConfirm("Remove success.", window.wxc.xcConfirm.typeEnum.success);
									location.href="./?page=user&sid=1";
								}else{
									
                                                                        window.wxc.xcConfirm("Remove failed.", window.wxc.xcConfirm.typeEnum.success);
									
								}
								
							}
				);
			}
			
			function jq_server_member_remove(uid,cid)
			{
				$.post(
							"./?ajax=server_member_remove&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid,'cid':cid},
							function(data) {
								if (data.length>0) {
                                                                        window.wxc.xcConfirm("Remove success.", window.wxc.xcConfirm.typeEnum.success);
								}else{
									location.href="?page=user&sid=1&action=show_members&cid="+cid;
									
									
								}
								
							}
				);
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
			function jq_server_reset_user_password(serverId,userId){
				
			var newPw = '123456';
				$.post(
							"./?ajax=server_regstration_genpw",
							{ 'serverId': serverId, 'userId': userId, 'newPw': newPw },
							function(data) {
								if (data.state==1) {
									alert('Password set to: ' + newPw);
									
								} else {
									alert('failed: '+data);
								}
								//jq_server_getRegistrations(serverId);
							},"json"
				);
			}
			
			function jq_user_updateUsername(uid, newVal)
			{
				$('#user_name_'+uid).append(imgAjaxLoading);
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
		function jq_user_add()
			{
				type =$("#type").val();
                fenceAlarm = $("#fenceAlarm").val();
				param =$("input").serialize();
			
				$.post("./?ajax=server_user_add&sid=1&fenceAlarm="+fenceAlarm+"&type="+type+"&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",param,
						function (data) {
							
							if(data.length == 0){
								
                                                                window.wxc.xcConfirm("Add success.", window.wxc.xcConfirm.typeEnum.success); 
								location.href="./?page=user&sid=1";
								
							}else{
								window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.warning);
								//$(".message").show().html(data);
							}
						}
					);
			}
			function jq_user_update()
			{
                uid =$("#uid").val();
                fenceAlarm = $("#fenceAlarm").val();
				param =$("input").serialize();
			
				$.post("./?ajax=server_user_update&sid=1&fenceAlarm="+fenceAlarm+"&uid="+uid+"&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",param,
						function (data) {
							
							if(data.length == 0){
								
                            window.wxc.xcConfirm("Update success.", window.wxc.xcConfirm.typeEnum.success); 
								location.href="./?page=user&sid=1";
								
							}else{
								window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.warning);
								//$(".message").show().html(data);
							}
						}
					);
			}

			function jq_user_renew()
			{
				type =$("#type").val();
				param =$("input").serialize();
			
				$.post("./?ajax=server_user_renew&sid=1&type="+type+"&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",param,
						function (data) {
							
							if(data.length == 0){
								
                                                                window.wxc.xcConfirm("Renew success.", window.wxc.xcConfirm.typeEnum.success); 
								//location.href="./?page=user&sid=1";
								
							}else{
								window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.warning);
								//$(".message").show().html(data);
							}
						}
					);
			}
                        function jq_set_member_level(channelId, userId)
			{
				level =$("#memberLevel"+userId).val();
			
				$.post("./?ajax=server_set_member_level&sid=1&level="+level+"&entId=<?php echo SessionManager::getInstance()->getLoginId();?>"+"&uid="+userId+"&cid="+channelId,null,
						function (data) {
							
							if(data.length == 0){
								
                                                                window.wxc.xcConfirm("Set priority success.", window.wxc.xcConfirm.typeEnum.success); 
								//location.href="./?page=user&sid=1";
								
							}else{
								window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.warning);
								//$(".message").show().html(data);
							}
						}
					);
			}

		function jq_member_add(cid)
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
			
			$.post('./?ajax=server_member_add&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>&cid='+cid, data, function(data){
				if(data.state == 1){
			                window.wxc.xcConfirm("Add success.", window.wxc.xcConfirm.typeEnum.success);	
					location.href = "?page=user&sid=1&action=show_members&cid="+cid;
				}else{
					alert("Add failed.");
				}
			}, 'json');	

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
			function jq_server_config_show(sid)
			{
				$.post("./?ajax=server_config_show",
						{ 'sid': sid },
						function(data){
							$('#jq_information').show().html(data);
						}
					);
			}

			function jq_server_search(){
				
				var kw =$("input[name=keyword]").val();
				var sle =$("#catlist").val();
				$.post(
				"./?ajax=server_search&sid=1",
				{'kw':kw,'sle':sle},
				function(data){
					
					$("#list").find("table").remove();
					$("#list").html(data);
					
					$(".pager").html("Total "+total+" items，total 1 page，current page 1 </div> ");
				
				}
		
				)
			
				
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
