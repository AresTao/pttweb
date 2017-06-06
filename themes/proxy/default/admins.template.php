<div id="dcWrap">
 <div id="dcHead">
 <div id="head">
  <div class="logo"><a href="./"><img src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/images/mlogo.gif" alt="logo"></a></div>
  <div class="nav">
   <ul>
    <li><a href="#" target="_blank">帮助</a></li>
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
  <?php 
      if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 1){
  ?>
	  <li><a href="?page=operator&sid=1"><i class="article"></i><em>二级代理商管理</em></a></li>
	  <li><a href="?page=enterprise1&sid=1"><i class="article"></i><em>企业用户管理</em></a></li>
	  <li><a href="?page=record1&sid=1"><i class="article"></i><em>记录管理</em></a></li>
  <?php
      }
      if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 2){
  ?> 
	  <li><a href="?page=enterprise2&sid=1"><i class="article"></i><em>企业用户管理</em></a></li>
	  <li><a href="?page=record2&sid=1"><i class="article"></i><em>记录管理</em></a></li>

  <?php
      }
  ?>
 </ul>

</div></div>
 <div id="dcMain">
   <!-- 当前位置 -->
<div id="urHere">管理中心<b>></b><strong>个人信息管理</strong> </div>   <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><!--<a href="?page=server&sid=1" class="actionBtn">返回列表</a>-->编辑信息</h3>
<?php
     $id = SessionManager::getInstance()->getLoginId();
     $operator= MysqlInterface::getOperatorById($id);
?>   
    <form action="" method="post" id="updateForm" name="updateForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="account" size="40" class="inpMain" id="account" readonly="true" value='<?php echo $operator['account'];?>'/>
        <input type="hidden" name="operatorId" size="40" class="inpMain" id="operatorId"  value='<?php echo $operator['id'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">账号密码</td>
       <td>
        <input type="text" name="passwd" size="40" class="inpMain" id="passwd"  value=""/>
       </td>
      </tr>
       <tr>
       <td width="100" align="right">确认密码</td>
       <td>
        <input type="text" name="confirmpasswd" size="40" class="inpMain" id="confirmpasswd"  value=""/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">姓名</td>
       <td>
        <input type="text" name="name" size="40" class="inpMain" id="name"  value='<?php echo $operator['name'];?>'/>
        <input type="hidden" name="comment" size="40" class="inpMain" id="comment"  value='<?php echo $operator['comment'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">邮箱</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value='<?php echo $operator['email'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">电话</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value='<?php echo $operator['phone'];?>'/>
       </td>
      </tr>

      <tr>
       <td width="100" align="right">可用年卡数</td>
       <td>
        <input type="text" name="availableCards" size="40" class="inpMain" id="availableCards" readonly="true" value='<?php echo $operator['availableCards'];?>'/>
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


	

	<div id="jq_information"></div>

<script type="text/javascript">
function jq_operator_update()
{
        id = $("#operatorId").val();
        param =$("input").serialize();

        /*      if(upwd!==rpwd){
                alert('密码不一致');

                }*/
        $.post("./?ajax=server_operator_update&id="+id,param,
                        function (data) {
                        if(data.length>0){

                        //location.href="./?page=user&sid=1";
                        $(".message").show().html(data);
                        window.location.href='./?page=admins&sid=1';

                        }else{
                        alert('更新失败');
                        //$(".message").show().html(data);
                        }
                        }
              );
}

$(function () {
$("#updateForm").validate({
   submitHandler:function() {
       jq_operator_update(); 
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
            minlength: jQuery.format("密码不能小于{0}个字符")
       },
       confirmpasswd: {
           minlength: "确认密码不能小于5个字符",
           equalTo: "两次输入密码不一致"
       },
       email: {
            required: "请输入Email地址",
            email: "请输入正确的email地址"
       },
       phone: {
           number: "请输入正确的电话号码",
           
       }
  }
});
});

</script>
