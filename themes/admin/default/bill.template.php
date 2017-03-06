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
           url: "./?ajax=server_getBills&page=bill&najax=1",
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
                        url: './?ajax=server_getBills&page=bill',      //提交到一般处理程序请求数据   
                        data: "pageIndex=" + (pageIndex+1) + "&pageSize=" + pageSize+"&sid=1",          //提交两个参数：pageIndex(页面索引)，pageSize(显示条数)                   
                        success: function(data) {
                             //移除Id为Result的表格里的行，从第二行开始（这里根据页面布局不同页变）   
							
                            $("#list").html(data);             //将返回的数据追加到表格   
						
							
                        }  
                    }); 
                }
            }); 

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
  <li><a href="?page=operator&sid=1"><i class="article"></i><em>代理商管理</em></a></li>
  <li><a href="?page=bill&sid=1"><i class="article"></i><em>账单管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">
  
   <?php
   if(!isset($_GET['action'])){
   ?>
<script>

</script>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>账单列表</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div>  <a href="./?page=bill&sid=1&action=add" class="actionBtn add">新建账单</a> 账单列表</h3>
<form action="?page=bill&action='lists'" method="post" enctype="multipart/form-data">	
       
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="filter">
    <form action="" method="post">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>搜索类型</label> 
     <select name="cat_id" id="catlist">
                  <option value="1">账单号</option>
		  <option value="2">代理商编号</option>
		  <option value="3">代理商姓名</option>
     </select>
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>账单状态</label> 
     <select name="billstatus" id="billstatus">
                  <option value="0" selected="selected">全部</option>
		  <option value="1">生效</option>
		  <option value="2">作废</option>
     </select>

     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_bill_search();return false;" />
     <input name="submit" class="btnGray" type="submit" value="导出为CSV文件" onclick="jq_bill_file_output();return false;" />
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
	$operators = MysqlInterface::getOperators();  
	  
	  

?>
<div id="urHere">管理中心<b>></b><strong>账单管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=bill&sid=1" class="actionBtn">返回列表</a>新建账单</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right">代理商</td>
       <td>
        <select name="operatorId" id="operatorId">
      <?php 
           foreach($operators as $member){
      ?>
           <option value ='<?php echo $member['id'];?>'><?php echo $member['name'];?></option>
      <?php
           }
      ?>
        </select>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">总账号数</td>
       <td>
        <input type="text" name="number" size="40" class="inpMain" id="number"  value=""/>
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
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_bill_add()" />
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
	$bill= MysqlInterface::getBillById($id);
	//var_dump($user);
		
	  

?>
<div id="urHere">管理中心<b>></b><strong>账单管理</strong> </div> 
  <div id="manager" class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="?page=bill&sid=1" class="actionBtn">返回列表</a>编辑账单</h3>
   <form action="" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
       <tr>
       <td width="100" align="right">代理商</td>
       <td>
        <select name="operatorId" id="operatorId">
      <?php 
           foreach($operators as $member){
               if ($member['id'] == $bill['operatorId'])
               {
      ?>
           <option value ='<?php echo $member['id'];?>' selected="selected"><?php echo $member['name'];?></option>
      <?php
               }
               else {
      ?>

           <option value ='<?php echo $member['id'];?>'><?php echo $member['name'];?></option>
      <?php    
               }
           }
       ?>
        </select>
        <input type="hidden" name="billId" size="40" class="inpMain" id="billId"  value='<?php echo $bill['id'];?>'/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right">总账号数</td>
       <td>
        <input type="text" name="number" size="40" class="inpMain" id="number"  value=""/>
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
      
        <input type="button" name="submit" class="btn" value="提交" onclick="jq_bill_update()" />
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
                        function jq_bill_add()
			{
				param =$("input").serialize();
			
			/*	if(upwd!==rpwd){
					alert('密码不一致');
					
				}*/
				$.post("./?ajax=server_bill_add&sid=1",param,
						function (data) {
							if(data.length>0){
								
								//location.href="./?page=user&sid=1";
								$(".message").show().html(data);
                                                                window.location.href='./?page=bill&sid=1';
								
							}else{
								alert('添加失败');
								//$(".message").show().html(data);
							}
						}
					);
			}
			

			function jq_bill_search(){
				
				var kw =$("input[name=keyword]").val();
				var type =$("#catlist").val();
				var billstatus =$("#billstatus").val();
				$.post(
				"./?ajax=server_bill_search&sid=1",
				{'type':type,'value':kw, 'status':billstatus},
				function(data){
					
					$("#list").find("table").remove();
					$("#list").html(data);
					
					$(".pager").html("");
				
				}
		
				)
			
				
			}
                        function jq_bill_remove(id){
				
				$.post(
				"./?ajax=server_bill_remove&id="+id,'',
				function(data){
                                         if (data.length > 0)
                                         {
                                                  window.location.href='./?page=bill&sid=1';
                                         }
                                         else{
                                                  alert("删除失败");
                                         }
				
				}
		
				)
			
				
			}
                        function jq_bill_update()
			{
                                id = $("#billId").val();
				param =$("input").serialize();
			
			/*	if(upwd!==rpwd){
					alert('密码不一致');
					
				}*/
				$.post("./?ajax=server_bill_update&id="+id,param,
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
			

		        function jq_bill_file_output(){
                                
                                var kw =$("input[name=keyword]").val();
                                var type =$("#catlist").val();
                                var form=$("<form>");//定义一个form表单
                                form.attr("style","display:none");
                                form.attr("target","");
                                form.attr("method","post");
                                form.attr("action","./?ajax=server_bill_file_output&sid=1");
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

                        
			//$('#jq_information').show().html($(parent).id());
		/*]]>*/
	</script>
