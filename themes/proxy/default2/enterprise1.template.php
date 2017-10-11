<script>
	
	var num;
    $(function(){
        $.ajax({
           cache: false,
           async: false,
           type: 'post',
           data: { sid: "1" },
		   dataType: "json",
           url: "./?ajax=server_getEnterprises_en&page=enterprise1&najax=1&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>",
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
						
						$(".pager").html("total "+num+" items，total 1 page，current page 1 </div> ");
					}					
                }  
                //请求数据   
                function InitTable(pageIndex) {                                  
                    $.ajax({   
                        type: "POST",  
                        url: './?ajax=server_getEnterprises_en&page=enterprise1&parentId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
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
     if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 1){
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
        <li class="noRight"><a href="#"> Agency ID：<?php echo $operator['id'];?></a></li>
        <li class="noRight"><a href="#"> Agency Name：<?php echo $operator['name'];?> </a> </li>
   </ul>
   <ul class="navRight">
    <li class="noLeft"><a href="#">Permanent Cards：<?php echo $operator['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">Normal Year Cards：<?php echo $operator['availableCards'];?></a></li>
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
  <li><a href="?page=operator&sid=1"><i class="article"></i><em>Secondary Agency</em></a></li>
  <li><a href="?page=enterprise1&sid=1"><i class="article"></i><em>Enterprise User</em></a></li>
  <li><a href="?page=record1&sid=1"><i class="article"></i><em>Record</em></a></li>

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
                        window.location.href='./?page=enterprise1&sid=1';
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
<div id="urHere">Admin<b>></b><strong>Enterprise User List</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" id="pldr" class="actionBtn add hint_trigger" >Batch In<span class="hintimg"></span></a><div style="width:10px;"></div>  <a href="./?page=enterprise1&sid=1&action=add" class="actionBtn add">Add New Enterprise</a> Enterprise User List</h3>
	<input type="file" id="fileToUpload" name="fileToUpload" class="hidden" value=""  style="display:none"/>
<form action="?page=enterprise&action='lists'" method="post" enctype="multipart/form-data">	
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="short-filter">
    <form action="" method="post">
     <div class="item-left">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>Search Type</label> 
     <select name="cat_id" id="catlist">
                  <option value="1">Account</option>
                  <option value="2">Name</option>
		  <option value="3">Email</option>
		  <option value="4">Telephone</option>
     </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div>
     <div class="btn-item">
     <input name="submit" class="btnGray" type="submit" value="Search" onclick="jq_enterprise_search();return false;" />
     <input name="submit" class="btnGray" type="submit" value="Save As CSV File" onclick="jq_enterprise_file_output();return false;" />
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
<div id="urHere">Admin<b></b><strong>Enterprise User List</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise1&sid=1" class="actionBtn">Back To List</a>Add New Enterprise</h3>
   <form action="" method="post" id="addForm" name="addForm">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">Account</td>
       <td>
        <input type="text" name="account" size="40" class="inpMain" id="account"  value=""/>
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
        <input type="text" name="name" size="40" class="inpMain" id="name"  value=""/>
       </td>
      </tr>
      <tr>

      <tr>
       <td width="100" align="right">Email</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" id="email"  value=""/><span>(Optional)</span>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Telephone</td>
       <td>
        <input type="text" name="phone" size="40" class="inpMain" id="phone"  value=""/><span>(Optional)</span>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Comment</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="comment"  value=""/><span>(Optional)</span>
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
      
        <input type="submit" name="submit" class="btn" value="Submit" />
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
<div id="urHere">Admin<b>></b><strong>Enterprise User</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise1&sid=1" class="actionBtn">Back To List</a>Edit Enterprise</h3>
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
       <td width="100" align="right">Comment</td>
       <td>
        <input type="text" name="comment" size="40" class="inpMain" id="comment"  value='<?php echo $enterprise['comment'];?>'/>
       </td>
      </tr>


      <tr>
       <td width="100" align="right">Available cards</td>
       <td>
        <input type="text" name="availableCards" size="40" class="inpMain" id="availableCards" readonly="true" value='<?php echo $enterprise['availableCards'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Available Pcards</td>
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
      
        <input type="submit" name="submit" class="btn" value="Submit" />
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
<div id="urHere">Admin<b>></b><strong>Enterprise User</strong> </div>
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=enterprise1&sid=1" class="actionBtn">Back To List</a>Dispatch</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">Enterprise ID</td>
       <td>
        <input type="text" name="enterpriseId" size="40" class="inpMain" id="enterpriseId" readonly="true" value="<?php echo $enterprise['id'];
?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">Account</td>
       <td>
        <input type="text" name="enterpriseAccount" size="40" class="inpMain" id="enterpriseAccount" readonly="true" value="<?php echo $enterprise['account'];?>"/>
        <input type="hidden" name="availableCards" size="40" class="inpMain" id="availableCards"  value="<?php echo $enterprise['availableCards'];?>"/>
        <input type="hidden" name="availablePCards" size="40" class="inpMain" id="availablePCards"  value="<?php echo $enterprise['availablePCards'];?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">Name</td>
       <td>
       <input type="text" name="enterpriseName" size="40" class="inpMain" id="enterpriseName" readonly="true" value="<?php echo $enterprise['name'];?>"/>
        </td>
      </tr>
      <tr>
       <td width="100" align="right">Add Card Count</td>
       <td>
        <input type="text" name="cardNum" size="40" class="inpMain" id="cardNum"  value="0"/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">Add PCard Count</td>
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
       <td width="100" align="right">Total Money</td>
       <td>
        <input type="text" name="cost" size="40" class="inpMain" id="cost"  value="0"/>
       </td>
      </tr>

      <tr>
       <td></td>
       <td>
       <input type="button" name="submit" class="btn" value="Submit" onclick="jq_dispatcher_enterprise_add()" />
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
                        //window.wxc.xcConfirm("添加成功", window.wxc.xcConfirm.typeEnum.success);
                        window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.success);
			window.location.href='./?page=enterprise1&sid=1';

			}else{
			//alert('添加失败');
                        window.wxc.xcConfirm("Add Failed", window.wxc.xcConfirm.typeEnum.error);
			//$(".message").show().html(data);
			}
			}
	      );
}


function jq_enterprise_search(){

	var kw =$("input[name=keyword]").val();
	var type =$("#catlist").val();
	$.post(
			"./?ajax=server_enterprise_search_en&sid=1&parentId="+<?php echo SessionManager::getInstance()->getLoginId();?>,
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
                        window.wxc.xcConfirm("Delete success.", window.wxc.xcConfirm.typeEnum.success);
			window.location.href='./?page=enterprise1&sid=1';
			}
			else{
			//alert("删除失败");
                        window.wxc.xcConfirm("Delete failed", window.wxc.xcConfirm.typeEnum.error);
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
                        window.wxc.xcConfirm("Update success.", window.wxc.xcConfirm.typeEnum.success);

			window.location.href='./?page=enterprise1&sid=1';
			}else{
			//alert('更新失败');
                        window.wxc.xcConfirm("Update failed.", window.wxc.xcConfirm.typeEnum.error);
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
                        window.wxc.xcConfirm("Dispatch success.", window.wxc.xcConfirm.typeEnum.success);
                        window.location.href='./?page=enterprise1&sid=1';

                        }else{
                        //alert('分配失败,'+data);
                        window.wxc.xcConfirm("Dispatch failed.", window.wxc.xcConfirm.typeEnum.error);
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
            remote:jQuery.format("Account has been registered"),
            required: "Please input account"
       },
       passwd: {
            required: "Please input password",
            minlength: jQuery.format("password should be longer than {0} ")
       },
       confirmpasswd: {
           required: "Please input confirm password",
           minlength: "Confirm password should be longer than {0}",
           equalTo: "Confirm password is not equal to password"
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
            minlength: jQuery.format("password should be longer than {0}")
       },
       confirmpasswd: {
           minlength: "Confirm password should be longer than {0}",
           equalTo: "Confirm password is not equal to password"
       }
  }
});

});

		
</script>
