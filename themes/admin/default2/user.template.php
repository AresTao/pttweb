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
           url: "./?ajax=server_getRegistrations&page=user&najax=1",
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
                    prev_text: "« 上一页",
                    next_text: "下一页 »",
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
						
						$(".pager").html("总计"+num+"个记录，共 1 页，当前第 1 页 </div> ");
					}					
                }  
                //请求数据   
                function InitTable(pageIndex) {                                  
                    $.ajax({   
                        type: "POST",  
                        url: './?ajax=server_getRegistrations&page=user',      //提交到一般处理程序请求数据   
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
   <ul>
    <li><a href="#" target="_blank">帮助</a></li>
    <li class="noRight"><a href="http://www.allptt.com">关于我们</a></li>
   </ul>
   <ul class="navRight">
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
  <li><a href="?page=user&sid=1"><i class="article"></i><em>用户管理</em></a></li>
  <li><a href="?page=server&sid=1"><i class="articleCat"></i><em>频道管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">
 <?php
   if(isset($_GET['action'])&&$_GET['action']=='show_members'){
   ?>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>成员列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div>  <a href="./?page=user&sid=1&action=member_add&cid=<?php echo $_GET['cid'] ?>" class="actionBtn add">添加成员</a> 成员列表</h3>

<?php
$srid =$_GET['sid'];
$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
$chs =$server->getChannelState($_GET['cid']);

//$chs =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']))->getChannels($_GET['sid'],$_GET['cid']);

$members =explode(",",$chs->getMembers());
//var_dump($members);
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
	$mr=intval($member);
	
		//var_dump($member);			
						//FIXME Ice version check, enum-index available? otherwise, one has to edit his slice file – actually, this fixme should be a general check, in install or general warning-disableable
			
			$users = $server->getRegisteredUsers();
	//foreach ($users AS $userId=>$userName) {
if(array_key_exists($mr,$users)){
//	foreach ($users AS $userId=>$userName) {
	$user = ServerInterface::getInstance()->getServerRegistration($srid,$mr);
	if($user->getUserId()!==0){
?>
	 
          <tr>
    
      <td align="center"><?php echo $user->getUserId();?></td>
      <td><?php echo $user->getName();?></td>
      <td align="center"><?php echo $user->getEmail();?></td>
	  

      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_server_member_remove(<?php echo $user->getUserId() ?>,<?php echo intval($_GET['cid'])?>);}">删除</a>
             </td>
     </tr>



<?php
	}
//}
	}else{
		
?>
          <tr>
    
      <td align="center"><?php echo $mr;?></td>
      <td></td>
      <td align="center"></td>
	  

      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_server_member_remove(<?php echo $mr ?>,<?php echo intval($_GET['cid'])?>);}">删除</a>
             </td>
     </tr>


<?php

		
	}
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
	  
	  		$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']));
			
			$chs =$server->getChannelState($_GET['cid']);
			$members =$chs->getMembers();
			$mbs = explode(",",$members);
			
			$users = $server->getRegisteredUsers();
		
   ?>
<div id="urHere">管理中心<b>></b><strong>成员管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1&action=show_members&cid=<?php echo $_GET['cid'];?>" class="actionBtn">返回列表</a>添加成员</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">用户名</td>
	<td width="98%">
	   <?php
	   $i=0;
	   foreach ($users as $key=>$user):
	   
	  /* echo $key;
	   echo "<pre>";
	   var_dump($mbs);*/
  if($key==0 || in_array($key,$mbs))continue;
	  
	   if($i!==0 &&$i%5==0)echo "<br>";
	   ?>
	    
        <label style="float:left;width:150px;" for="uname<?php echo $key;?>"><input type="checkbox" name="uid"  class="inpMain" id="uname<?php echo $key;?>"   value="<?php echo $key;?>"/><?php echo $user?></label>
		
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
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_member_add(<?php echo $_GET['cid'];?>)" />
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
  <div class="filter">
    <form action="" method="post">
     <select name="cat_id" id="catlist">
      <option value="0">全部</option>
                  <option value="1">在线用户 </option>
                        <option value="2"> 离线用户</option>

						
                 </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_server_search();return false;" />
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
<div id="urHere">管理中心<b>></b><strong>用户管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=user&sid=1" class="actionBtn">返回列表</a>新建用户</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">用户名</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value=""/><label class="inps" style="color:#ccc;">&nbsp;用户默认密码为123456</label>
       </td>
      </tr>
<!---
      <tr>
       <td align="right">密码</td>
       <td>
        <input type="password" name="pwd" size="40" class="inpMain" id="upwd" value="" />
       </td>
      </tr>
      <tr>
       <td align="right">确认密码</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" id="rpwd" />
       </td>
      </tr>-->
      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_user_add()" />
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
	
	$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid));
	$user = ServerInterface::getInstance()->getUserName($sid, $uid);
	//var_dump($user);
		
	  

?>
<div id="urHere">管理中心<b>></b><strong>用户管理</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="article.html" class="actionBtn">返回列表</a>添加用户</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">用户名称</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value="<?php echo $user;?>"/>
       </td>
      </tr>

      <tr>
       <td align="right">密码</td>
       <td>
        <input type="password" name="pwd" size="40" class="inpMain" id="upwd" value="" />
       </td>
      </tr>
      <tr>
       <td align="right">确认密码</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" id="rpwd" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_user_add()" />
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
		/*<![CDATA[*/
	
			function jq_server_setSuperuserPassword(sid)
			{
				//$('#li_server_superuserpassword > .ajax_info').html(imgAjaxLoading);
				var pw = '123456';
				//var sid = <?php isset($_GET['sid'])?$_GET['sid']:1; ?>;
				//alert(pw);
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
							"./?ajax=server_regstration_remove",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid },
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
								}else{
									location.href="./?page=user&sid=1";
									
									
								}
								
							}
				);
			}
			
			function jq_server_member_remove(uid,cid)
			{
				$.post(
							"./?ajax=server_member_remove",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid,'cid':cid},
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
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
				//uname =$("#uname").val();
			//	upwd=$("#upwd").val();
			//	rpwd=$("#rpwd").val();
				param =$("input").serialize();
			
			/*	if(upwd!==rpwd){
					alert('密码不一致');
					
				}*/
				$.post("./?ajax=server_user_add&sid=1",param,
						function (data) {
							
							if(data.length>0){
								
								//location.href="./?page=user&sid=1";
								$(".message").show().html(data);
								
							}else{
								alert('添加失败');
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
			
			$.post('./?ajax=server_member_add&sid=1&cid='+cid, data, function(data){
				if(data.state == 1){
				
					location.href = "?page=user&sid=1&action=show_members&cid="+cid;
				}else{
					alert("操作失败");
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
					
					$(".pager").html("总计"+total+"个记录，共 1 页，当前第 1 页 </div> ");
				
				}
		
				)
			
				
			}
	

		
			//$('#jq_information').show().html($(parent).id());
		/*]]>*/
	</script>
