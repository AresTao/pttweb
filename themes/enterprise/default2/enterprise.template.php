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
           url: "./?ajax=server_getEnterprises_en&page=enterprise&najax=1",
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
                        url: './?ajax=server_getEnterprises&page=enterprise',      //提交到一般处理程序请求数据   
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
     if (SessionManager::getInstance()->isEnterprise() && SessionManager::getInstance()->getLevel() == 1){
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
   <ul>
    <li><a href="#" target="_blank">帮助</a></li>
    <li class="noRight"><a href="http://www.allptt.com">关于我们</a></li>
   </ul>
   <ul class="navRight">
    <li class="M noLeft"><a href="JavaScript:void(0);">您好，<?php echo SessionManager::getInstance()->getLoginName();?></a>
     <div class="drop mUser">
      <a href="?page=admins&id=<?php echo SessionManager::getInstance()->getLoginId(); ?>">编辑我的个人资料</a>
     </div>
    </li>
    <li class="noRight"><a href="?page=logout">退出</a></li>
   </ul>
  </div>
 </div>
</div>
<!-- dcHead 结束 --> <div id="dcLeft"><div id="menu">

  <ul>
  <li><a href="?page=enterprise&sid=1"><i class="article"></i><em>二级管理员管理</em></a></li>
  <li><a href="?page=bill&sid=1"><i class="article"></i><em>账单管理</em></a></li>

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
                    url:'./?ajax=batch_add_enterprise&uid=<?php echo SessionManager::getInstance()->getLoginId();?>',  
		    secureuri:false,  
		    fileElementId:'fileToUpload',//file标签的id  
		    dataType: 'json',//返回数据的类型  
		    data:'',//一同上传的数据  
		    success: function (data, status) {  
		        //$("#upload").attr("src", "../image/"+obj.fileName);  

		        alert(obj.reason);  
                        window.location.href='./?page=enterprise&sid=1';
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
        <h3><a href="" id="pldr" class="actionBtn add hint_trigger" >批量导入<span class="hintimg"></span></a><div style="width:10px;"></div>  <a href="./?page=enterprise&sid=1&action=add" class="actionBtn add">新建企业用户</a> 企业用户列表</h3>
	<input type="file" id="fileToUpload" name="fileToUpload" class="hidden" value=""  style="display:none"/>
<form action="?page=enterprise&action='lists'" method="post" enctype="multipart/form-data">	
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="filter">
    <form action="" method="post">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>搜索类型</label> 
     <select name="cat_id" id="catlist">
                  <option value="1">账号</option>
		  <option value="2">邮箱</option>
		  <option value="3">电话</option>
     </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_enterprise_search();return false;" />
     <input name="submit" class="btnGray" type="submit" value="导出为CSV文件" onclick="jq_enterprise_file_output();return false;" />
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
    <h3><a href="?page=enterprise&sid=1" class="actionBtn">返回列表</a>新建企业用户</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value=""/>
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
       <td width="100" align="right">邮箱</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value=""/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">电话</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value=""/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">备注</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="comment"  value=""/>
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
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_enterprise_add()" />
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
    <h3><a href="?page=enterprise&sid=1" class="actionBtn">返回列表</a>编辑企业用户</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">账号</td>
       <td>
        <input type="text" name="uname" size="40" class="inpMain" id="uname"  value='<?php echo $enterprise['name'];?>'/>
        <input type="hidden" name="enterpriseId" size="40" class="inpMain" id="enterpriseId"  value='<?php echo $enterprise['id'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">账号密码</td>
       <td>
        <input type="text" name="passwd" size="40" class="inpMain" id="passwd"  value='<?php echo $enterprise['passwd'];?>'/>
       </td>
      </tr>
       <tr>
       <td width="100" align="right">确认密码</td>
       <td>
        <input type="text" name="confirmpasswd" size="40" class="inpMain" id="confirmpasswd"  value=""/>
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
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_enterprise_update()" />
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
								
								$(".message").show().html(data);
                                                                window.location.href='./?page=enterprise&sid=1';
								
							}else{
								alert('添加失败');
								//$(".message").show().html(data);
							}
						}
					);
			}
			

			function jq_enterprise_search(){
				
				var kw =$("input[name=keyword]").val();
				var type =$("#catlist").val();
				$.post(
				"./?ajax=server_enterprise_search&sid=1",
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
				form.attr("action","./?ajax=server_enterprise_file_output&sid=1");
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
				"./?ajax=server_enterprise_remove&id="+id,'',
				function(data){
                                         if (data.length > 0)
                                         {
                                                  window.location.href='./?page=enterprise&sid=1';
                                         }
                                         else{
                                                  alert("删除失败");
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
				$.post("./?ajax=server_enterprise_update&id="+id,param,
						function (data) {
							if(data.length>0){
								
								//location.href="./?page=user&sid=1";
								$(".message").show().html(data);
								
								window.location.href='./?page=enterprise&sid=1';
							}else{
								alert('添加失败');
								//$(".message").show().html(data);
							}
						}
					);
			}
			

		
			//$('#jq_information').show().html($(parent).id());
		/*]]>*/
	</script>
