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
    <li class="M noLeft"><a href="JavaScript:void(0);">您好，admin</a>
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
   <!-- 当前位置 -->
<div id="urHere">管理中心<b>></b><strong>网站管理员</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=server&sid=1" class="actionBtn">返回列表</a>编辑管理员</h3>
   
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
   <tr style="display:none;">
       <td width="100" align="right">管理员名称</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" value="admin" readonly/>
       </td>
      </tr>

      <tr>
       <td align="right">密码</td>
       <td>
        <input type="password" name="upwd" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td align="right">确认密码</td>
       <td>
        <input type="password" name="reupwd" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_admin_update();" />
       </td>
      </tr>
     </table>
  
                   </div>
 </div>


	

	<div id="jq_information"></div>

	<script type="text/javascript">
		//<![CDATA[
		var admins_list_expanded = false;
		var adminGroups_list_expanded = false;

		function randomString(length)
		{
			var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz§$%&/()=?!{[]}";
			var str = '';
			for (i = 0; i < length; i++) {
				var r = Math.floor(Math.random() * chars.length);
				str += chars.substring(r, r+1);
			}
			return str;
		}

		/********************************************************************
		/* Admins
		/********************************************************************/

		function jq_admins_list_display()
		{
			$.post("./?ajax=db_admins_echo",
					{  },
					function(data){
						$('#admins_list > div.content').html(data);
					}
				);
		}

		function jq_admins_list_toggle()
		{
			if (!admins_list_expanded) {
				window.location.hash = 'admins';
				$('#admins_list > div.head > a > .indicator').html('-');
				jq_admins_list_display();
				admins_list_expanded = true;
			} else {
				window.location.hash = '';
				<?php // TODO: a refresh link would be useful ?>
				$('#admins_list > div.head > a > .indicator').html('+');
				$('#admins_list > div.content').html('');
				admins_list_expanded = false;
			}
		}

		function jq_admin_update_name(id)
		{
			$.post("./?ajax=db_admin_update_name",
					{ 'name': $('#admin_list_'+id+' > td:first > input').attr() },
					function(data) {
						if (data.length>0) { alert('failed: '+data); }else{
						alert('密码修改成功');
						
					}
					}
				);
		}

		function jq_admin_add_display()
		{
			$('#admin_area > div.content').html(
					'<div class="admin_add_form">'
					+ 'Name: <input type="text" name="name"/>'
					+ 'Pass: <input type="text" name="pw"/>'
					+ 'is global admin?: <input type="checkbox" name="isGlobalAdmin"/><br/>'
					+ '<input type="submit" onclick="jq_admin_add();" value="Add"/>'
					+ '<input type="button" onclick="$(\'#admin_area > div.content\').html(\'\');" value="Cancel"/>'
					+ ' </div>'
				);
		}

		function jq_admin_update()
		{
			var name = $("input[name='uname']").val();
			var pw = $("input[name='upwd']").val();
		//	alert(pw);
			//var isGlobalAdmin = $(".admin_add_form input[name='isGlobalAdmin']").attr('checked');
			var rpw =$("input[name='reupwd']").val();
		if( pw.length=='' ){
			
			alert('密码不能为空！');
			return false;
		}
			if(pw!==rpw){
				
				alert('密码不一致');
				return false;
			}
			$.post(
					"./?ajax=db_admin_update_name",
					{ 'uname': name, 'upwd': pw},
					function(data)
					{
						alert(data);
						
					}
				);
		}

		function jq_admin_remove(id)
		{
			if (!confirm('Are you sure you want to remove this admin account?')) {
				return ;
			}
			$.post("./?ajax=db_admin_remove",
					{ 'id': id },
					function(data)
					{
						if (data.length>0) {
							$('#jq_information').html('Failed: '+data);
						} else {
							$('#jq_information').html('Admin account removed.');
						}
						jq_admins_list_display();
					}
				);
		}



		/********************************************************************
		/*** Init
		/********************************************************************/
		$('document').ready(function(){
				if (window.location.hash == '#admins') {
					jq_admins_list_toggle();
				}
				if (window.location.hash == '#showAdminGroups') {
					jq_adminGroups_list_toggle();
				}
	
			});
		//]]>
	</script>
