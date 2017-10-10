<div id="dcWrap">
 <div id="dcHead">
 <div id="head">
  <div class="logo"><a href="./"><img src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/images/mlogo.gif" alt="logo"></a></div>
  <div class="nav">
   <ul>
    <li><a href="#" target="_blank">HELP</a></li>
   </ul>
   <ul class="navRight">
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
  <li><a href="?page=user&sid=1"><i class="article"></i><em>User Manage</em></a></li>
  <li><a href="?page=server&sid=1"><i class="articleCat"></i><em>Channel Manage</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="articleCat"></i><em>Monitor</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>Record</em></a></li>
 </ul>

</div></div>
 <div id="dcMain">
   <!-- 当前位置 -->
<div id="urHere">Admin<b>></b><strong>Edit Info</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><!--<a href="?page=server&sid=1" class="actionBtn">返回列表</a>-->Edit Info</h3>
<?php
     $id = SessionManager::getInstance()->getLoginId();
     $enterprise= MysqlInterface::getEnterpriseById($id);
?>
   <form action="" method="post" id="updateForm" name="updateForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">Enterprise ID</td>
       <td>
        <input type="text" name="enterpriseId" size="40" class="inpMain" id="enterpriseId" readonly="true" value='<?php echo $enterprise['id'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Account</td>
       <td>
        <input type="text" name="account" size="40" class="inpMain" id="account" readonly="true" value='<?php echo $enterprise['account'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Password</td>
       <td>
        <input type="text" name="passwd" size="40" class="inpMain" id="passwd"  value=""/>
       </td>
      </tr>
       <tr>
       <td width="100" align="right">Confirm Password</td>
       <td>
        <input type="text" name="confirmpasswd" size="40" class="inpMain" id="confirmpasswd"  value=""/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Name</td>
       <td>
        <input type="text" name="name" size="40" class="inpMain" id="name" value='<?php echo $enterprise['name'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Email</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value='<?php echo $enterprise['email'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Telephone</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value='<?php echo $enterprise['phone'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Available Cards</td>
       <td>
        <input type="text" name="availableCards" size="40" class="inpMain" id="availableCards" readonly="true" value='<?php echo $enterprise['availableCards'];?>'/>
        <input type="hidden" name="comment" size="40" class="inpMain" id="comment"  value='<?php echo $enterprise['comment'];?>'/>
       </td>
      </tr>

      <tr>
       <td></td>
       <td>

        <input type="submit" name="submit" class="btn" value="提交" />
       </td>
      </tr>
     </table>
    </form>
                <div class="message"></div>
                   </div>
		   </div>
 </div>


	

	<div id="jq_information"></div>

<script type="text/javascript">
function jq_enterprise_update()
{
	id = $("#enterpriseId").val();
	param =$("input").serialize();

	/*      if(upwd!==rpwd){
		alert('密码不一致');

		}*/
	$.post("./?ajax=server_enterprise_update&parentId="+<?php echo SessionManager::getInstance()->getLoginId();?>+"&id="+id,param,
			function (data) {
			if(data.length>0){

			//location.href="./?page=user&sid=1";
			$(".message").show().html(data);

			window.location.href='./?page=admins&sid=1';
			}else{
			alert('Update failed.');
			//$(".message").show().html(data);
			}
			}
	      );
}
$(function () {
$("#updateForm").validate({
   submitHandler:function() {
       jq_enterprise_update(); 
   },
   rules: {
       passwd: {
           minlength: 5
       },
       confirmpasswd: {
           minlength: 5,
           equalTo: "#passwd"
       },
       email: {
           required: true,
           email: true
       },
       phone: {
           number: true
       }
  },
  messages: {
       passwd: {
            minlength: jQuery.format("Password can't be {0} letters")
       },
       confirmpasswd: {
           minlength: "Confirm password should be longer than {0}",
           equalTo: "Confirm password is not equal to password"
       },
       email: {
            required: "Please input email",
            email: "Please input correct email"
       },
       phone: {
           number: "Please input correct phone number",
           
       }
  }
});
});
</script>
