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
           url: "./?ajax=server_getEnterprises&page=enterprise2&najax=1&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>",
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
                        url: './?ajax=server_getEnterprises&page=enterprise2&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
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
     //鉴权 只有一级代理商可以查看
     if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 2){
?>
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
     $operator = MysqlInterface::getOperatorById($id);

?>

   <ul>
        <li class="noRight"><a href="#"> 代理商编号：<?php echo $operator['id'];?></a></li>
        <li class="noRight"><a href="#"> 代理商名称：<?php echo $operator['name'];?> </a> </li>
   </ul>
   <ul class="navRight">
   <li class="noLeft"><a href="#">可用永久卡：<?php echo $operator['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">可用年卡：<?php echo $operator['availableCards'];?></a></li>
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
  <li><a href="?page=enterprise2&sid=1"><i class="article"></i><em>企业用户管理</em></a></li>
  <li><a href="?page=record2&sid=1"><i class="article"></i><em>记录管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">
  
   <?php
   if(!isset($_GET['action'])){
   ?>
<script>
$(function(){
	
	$('#pldr').click(function(e){
		
		e.preventDefault();
		$('#fileToUpload').trigger('click');
		
	})
	$('#fileToUpload').change(function(){
		$.ajaxFileUpload({  
                    url:'./?ajax=batch_add_enterprise&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>',  
		    secureuri:false,  
		    fileElementId:'fileToUpload',//file标签的id  
		    dataType: 'json',//返回数据的类型  
		    data:'',//一同上传的数据  
		    success: function (data, status) {  
		        //$("#upload").attr("src", "../image/"+obj.fileName);  

		        alert(obj.reason);  
                        window.location.href='./?page=enterprise2&sid=1';
		    },  
                    error: function (data, status, e) {  
                            alert(e);  
                    }  
		});
		//alert('提交成功');
	});
	/*$("#catlist").change(function(){
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
		
	})*/
	
})

</script>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>企业用户列表</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" id="pldr" class="actionBtn add hint_trigger" >批量导入<span class="hintimg"></span></a><div style="width:10px;"></div>  <a href="./?page=enterprise2&sid=1&action=add" class="actionBtn add">新建企业用户</a> 企业用户列表</h3>
	<input type="file" id="fileToUpload" name="fileToUpload" class="hidden" value=""  style="display:none"/>
<form action="?page=enterprise&action='lists'" method="post" enctype="multipart/form-data">	
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="short-filter">
    <form action="" method="post">
     <div class="item-left">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>搜索类型</label> 
     <select name="cat_id" id="catlist">
                  <option value="1">账号</option>
                  <option value="2">联系人</option>
		  <option value="3">邮箱</option>
		  <option value="4">电话</option>
     </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div> 
     <div class="btn-item">
     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_enterprise_search();return false;" />
     <input name="submit" class="btnGray" type="submit" value="导出为CSV文件" onclick="jq_enterprise_file_output();return false;" />
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
<div id="urHere">管理中心<b></b><strong>企业用户管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise2&sid=1" class="actionBtn">返回列表</a>新建企业用户</h3>
   <form action="" method="post" id="addForm" name="addForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="account" size="40" class="inpMain" id="account"  value=""/>
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
       <td width="100" align="right">联系人姓名</td>
       <td>
        <input type="text" name="name" size="40" class="inpMain" id="name"  value=""/>
       </td>
      </tr>
      <tr>

      <tr>
       <td width="100" align="right">邮箱</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value=""/><span>(可选)</span>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">电话</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value=""/><span>(可选)</span>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">备注</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="comment"  value=""/><span>(可选)</span>
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
      
        <input type="submit" name="submit" class="btn" value="提交"/>
       </td>
      </tr>
     </table>
    </form>
		<div class="message"></div>
                   </div>
				   
				   
	<?php
	}
 if(isset($_GET['action']) && $_GET['action']=='edit'){
	
	$id =intval($_GET['id']);
	
	//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid));
	$enterprise= MysqlInterface::getEnterpriseById($id);
	//var_dump($user);
		
	  

?>
<div id="urHere">管理中心<b>></b><strong>企业用户管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise2&sid=1" class="actionBtn">返回列表</a>编辑企业用户</h3>
   <form action="" method="post" id="updateForm" name="updateForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">账户编号</td>
       <td>
        <input type="text" name="enterpriseId" size="40" class="inpMain" id="enterpriseId" readonly="true" value='<?php echo $enterprise['id'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="account" size="40" class="inpMain" id="account" readonly="true" value='<?php echo $enterprise['account'];?>'/>
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
       <td width="100" align="right">联系人</td>
       <td>
        <input type="text" name="name" size="40" class="inpMain" id="name" value='<?php echo $enterprise['name'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">邮箱</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value='<?php echo $enterprise['email'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">电话</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value='<?php echo $enterprise['phone'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">备注</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="comment"  value='<?php echo $enterprise['comment'];?>'/>
       </td>
      </tr>


      <tr>
       <td width="100" align="right">可用年卡数</td>
       <td>
        <input type="text" name="availableCards" size="40" class="inpMain" id="availableCards" readonly="true" value='<?php echo $enterprise['availableCards'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">可用永久卡数</td>
       <td>
        <input type="text" name="availablePCards" size="40" class="inpMain" id="availablePCards" readonly="true" value='<?php echo $enterprise['availablePCards'];?>'/>
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
      
        <input type="submit" name="submit" class="btn" value="提交" />
       </td>
      </tr>
     </table>
    </form>
		<div class="message"></div>
                   </div>
	
				   
	<?php
	}if(isset($_GET['action']) && $_GET['action']=='dispatcher'){
            $id =intval($_GET['id']);
            $enterprise =  MysqlInterface::getEnterpriseById($id);
	?>
<div id="urHere">管理中心<b>></b><strong>企业用户管理</strong> </div>
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise2&sid=1" class="actionBtn">返回列表</a>分配</h3>
   <form action="" method="post" id="dispatchForm" name="dispatchForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">客户编号</td>
       <td>
        <input type="text" name="enterpriseId" size="40" class="inpMain" id="enterpriseId" readonly="true" value="<?php echo $enterprise['id'];
?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="enterpriseAccount" size="40" class="inpMain" id="enterpriseAccount" readonly="true" value="<?php echo $enterprise['account'];?>"/>
        <input type="hidden" name="availableCards" size="40" class="inpMain" id="availableCards"  value="<?php echo $enterprise['availableCards'];?>"/>
        <input type="hidden" name="availablePCards" size="40" class="inpMain" id="availablePCards"  value="<?php echo $enterprise['availablePCards'];?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">联系人姓名</td>
       <td>
       <input type="text" name="enterpriseName" size="40" class="inpMain" id="enterpriseName" readonly="true" value="<?php echo $enterprise['name'];?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">增加年卡数</td>
       <td>
        <input type="text" name="cardNum" size="40" class="inpMain" id="cardNum"  value="0"/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">增加永久卡数</td>
       <td>
        <input type="text" name="pCardNum" size="40" class="inpMain" id="pCardNum"  value="0"/>
       </td>
      </tr>

      <!--<tr>
       <td width="100" align="right">增加群组数</td>
       <td>
        <input type="text" name="groupNum" size="40" class="inpMain" id="groupNum"  value=""/>
       </td>
      </tr>-->
       <tr>
       <td width="100" align="right">金额</td>
       <td>
        <input type="text" name="cost" size="40" class="inpMain" id="cost"  value=""/>
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
<?php
        }
    }
        ?>
	</div>



	<hr/>

	<div id="jq_information"></div>

	<script type="text/javascript">
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
			
		        function jq_enterprise_add()
			{
				param =$("input").serialize();
			
			/*	if(upwd!==rpwd){
					alert('密码不一致');
					
				}*/
				$.post("./?ajax=server_enterprise_add&sid=1&uid="+<?php echo SessionManager::getInstance()->getLoginId();?>,param,
						function (data) {
							if(data.length>0){
								
								//$(".message").show().html(data);
							window.wxc.xcConfirm("添加成功", window.wxc.xcConfirm.typeEnum.success);
                                                                window.location.href='./?page=enterprise2&sid=1';
								
							}else{
								//alert('添加失败');
							window.wxc.xcConfirm("添加失败", window.wxc.xcConfirm.typeEnum.error);
								//$(".message").show().html(data);
							}
						}
					);
			}
			

			function jq_enterprise_search(){
				
				var kw =$("input[name=keyword]").val();
				var type =$("#catlist").val();
				$.post(
				"./?ajax=server_enterprise_search&sid=1&parentId="+<?php echo SessionManager::getInstance()->getLoginId();?>,
				{'type':type,'value':kw},
				function(data){
					
					$("#list").find("table").remove();
					$("#list").html(data);
					
					$(".pager").html("");
				
				}
		
				)
			
				
			}
                        function jq_enterprise_file_output(){
				
				var kw =$("input[name=keyword]").val();
				var type =$("#catlist").val();
                                var form=$("<form>");//定义一个form表单
				form.attr("style","display:none");
				form.attr("target","");
				form.attr("method","post");
				form.attr("action","./?ajax=server_enterprise_file_output&sid=1&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>");
				var input1=$("<input>");
				input1.attr("type","hidden");
				input1.attr("name","type");
				input1.attr("value",type);
                                var input2=$("<input>");
                                input2.attr("type","hidden");
                                input2.attr("name","value");
                                input2.attr("value",kw);
				$("body").append(form);//将表单放置在web中
				form.append(input1);
				form.append(input2);

				form.submit();//表单提交 
			}

                        function jq_enterprise_remove(id){
				
				$.post(
				"./?ajax=server_enterprise_remove&parentId="+<?php echo SessionManager::getInstance()->getLoginId();?>+"&id="+id,'',
				function(data){
                                         if (data.length > 0)
                                         {
					 window.wxc.xcConfirm("删除成功", window.wxc.xcConfirm.typeEnum.success);
                                                  window.location.href='./?page=enterprise2&sid=1';
                                         }
                                         else{
                                                  //alert("删除失败");
					 window.wxc.xcConfirm("删除失败", window.wxc.xcConfirm.typeEnum.error);
                                         }
				
				}
		
				)
			
				
			}
                        function jq_enterprise_update()
			{
                                id = $("#enterpriseId").val();
				param =$("input").serialize();
			
			/*	if(upwd!==rpwd){
					alert('密码不一致');
					
				}*/
				$.post("./?ajax=server_enterprise_update&parentId="+<?php echo SessionManager::getInstance()->getLoginId();?>+"&id="+id,param,
						function (data) {
							if(data.length>0){
								
								//location.href="./?page=user&sid=1";
								//$(".message").show().html(data);
							window.wxc.xcConfirm("更新成功", window.wxc.xcConfirm.typeEnum.success);
								
								window.location.href='./?page=enterprise2&sid=1';
							}else{
								//alert('更新失败');
							window.wxc.xcConfirm("更新失败", window.wxc.xcConfirm.typeEnum.error);
								//$(".message").show().html(data);
							}
						}
					);
			}
			
function jq_dispatcher_enterprise_add(){
        param =$("input").serialize();

        /*      if(upwd!==rpwd){
                alert('密码不一致');

                }*/
        $.post("./?ajax=server_dispatcher_enterprise_add&sid=1&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>",param,
                        function (data) {
                        if(data.length>0 && data=="succeed!"){

                        //location.href="./?page=user&sid=1";
                        //$(".message").show().html(data);
                        window.wxc.xcConfirm("分配成功", window.wxc.xcConfirm.typeEnum.success);
                        window.location.href='./?page=enterprise2&sid=1';

                        }else{
                        //alert('分配失败,'+data);
                        window.wxc.xcConfirm("分配失败", window.wxc.xcConfirm.typeEnum.error);
                        //$(".message").show().html(data);
                        }
                        }
              );
}
$(function () {
$("#addForm").validate({
   submitHandler:function() {
       jq_enterprise_add(); 
   },
   rules: {
       account: {
           remote:{
               url: "./?ajax=server_checkEnterprise&page=enterprise1",     //后台处理程序
               type: "post",               //数据发送方式
               data: {                     //要传递的数据
                   account: function() {
                       return $("#account").val();
                   }
               }
           },
           required: true
       },
       passwd: {
           required: true,
           minlength: 5
       },
       confirmpasswd: {
           required: true,
           minlength: 5,
           equalTo: "#passwd"
       }
  },
  messages: {
       account:{
            remote:jQuery.format("账号已经被注册"),
            required: "请输入账号"
       },
       passwd: {
            required: "请输入密码",
            minlength: jQuery.format("密码不能小于{0}个字符")
       },
       confirmpasswd: {
           required: "请输入确认密码",
           minlength: "确认密码不能小于5个字符",
           equalTo: "两次输入密码不一致"
       }
  }
});
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
       }
  },
  messages: {
       passwd: {
            minlength: jQuery.format("密码不能小于{0}个字符")
       },
       confirmpasswd: {
           minlength: "确认密码不能小于5个字符",
           equalTo: "两次输入密码不一致"
       }
  }
});
$("#dispatchForm").validate({
    submitHandler:function() {
        jq_dispatcher_enterprise_add();
    },

    rules: {
        cardNum: {
            required: true,
            number: true,
            max:100000,
            min:0
            },
        pCardNum: {
            required: true,
            number: true,
            max:100000,
            min:0
            },
        cost: {
            required: true,
            number: true,
            min:0
              }
           },
    messages: {
        cardNum: {
            required: "请输入年卡数",
            number:"请输入数字",
            max:"请不要超过100000",
            min:"请不要输入负数"
         },
        pCardNum: {
            required: "请输入年卡数",
            number:"请输入数字",
            max:"请不要超过100000",
            min:"请不要输入负数"

          },
        cost: {
            required: "请输入金额",
            number: "请输入数字",
            min: "请不要输入负数"
        }
    }
});
});
	
	</script>
