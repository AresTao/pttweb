<script>
	
	var num;
	
         var pageIndex = 0;     //页面索引初始值   
         var pageSize=20;
			
		 
         function record()
         {
		 $.ajax
	         ({
                     cache: false,
		     async: false,
		     type: 'post',
		     data: { sid: "1" },
		     dataType: "json",
		     url: "./?ajax=server_getRecordsByEnterprise&page=record&najax=1&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>",
		     success: function (data) { 
		     num =data;

		     }
		     });


                 InitTable(0);
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
                function PageCallback(index, jq) {             
                    InitTable(index); 
		    if(num<20){
						
			$(".pager").html("总计"+num+"个记录，共 1 页，当前第 1 页 </div> ");
		    }					
                }       

         }//翻页调用   
          
                //请求数据   
         function InitTable(pageIndex) {                                  
                    $.ajax({   
                        type: "POST",  
                        url: './?ajax=server_getRecordsByEnterprise&page=record&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
                        data: "pageIndex=" + (pageIndex+1) + "&pageSize=" + pageSize+"&sid=1",          //提交两个参数：pageIndex(页面索引)，pageSize(显示条数)                   
                        success: function(data) {
                             //移除Id为Result的表格里的行，从第二行开始（这里根据页面布局不同页变）   
							
                            $("#list").html(data);             //将返回的数据追加到表格   
						
							
                        }  
                    }); 
         }
         $(function () {
              record();
           }); 

            $(function(){
                 $('#startTime').datetimepicker({
                     showSecond: true,
                     showMillisec: false,
                     timeFormat: 'hh:mm:ss'
                 });
                  $('#endTime').datetimepicker({
                     showSecond: true,
                     showMillisec: false,
                     timeFormat: 'hh:mm:ss'
                 });
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
    <?php
     $id = SessionManager::getInstance()->getLoginId();
     $enterprise = MysqlInterface::getEnterpriseById($id);

?>
    <li class="noLeft"><a href="#">可用永久卡数：<?php echo $enterprise['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">可用年卡数：<?php echo $enterprise['availableCards'];?></a></li>
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
  <li><a href="?page=monitor&sid=1"><i class="articleCat"></i><em>监控管理</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>交易记录</em></a></li>
 </ul>

</div></div>
 <div id="dcMain">
  
   <?php
   if(!isset($_GET['action'])){
   ?>
<script>

</script>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>记录列表</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div> 记录列表</h3>
<form action="?page=record&action='lists'" method="post" enctype="multipart/form-data">	
       
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="filter">
    <form action="" method="post">
    <div id = "search" style="">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>搜索类型</label> 
     <select name="cat_id" id="catlist">
		  <option value="1">代理商编号</option>
		  <option value="2">代理商姓名</option>
     </select>
     
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div>

     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>开始时间</label><input type="text" id="startTime" class="inpMain" name="startTime" />
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>结束时间</label><input type="text" id="endTime" class="inpMain" name="endTime"/>

     <input name="submit" class="btnGray" type="submit" value="搜索" onclick="jq_record_search();return false;" />
     <!--<input name="submit" class="btnGray" type="submit" value="导出为CSV文件" onclick="jq_record_file_output();return false;" />-->
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
		

			function jq_record_search(){
				
				var kw =$("input[name=keyword]").val();
				var type =$("#catlist").val();
                                var startTime = $("#startTime").val();
                                var endTime = $("#endTime").val();
				$.post(
				"./?ajax=server_record_searchByEnterprise&sid=1&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>&type=1",
				{'type':type,'value':kw, 'startTime':startTime, 'endTime':endTime},
				function(data){
					
					$("#list").find("table").remove();
					$("#list").html(data);
					
					$(".pager").html("");
				
				}
		
				)
			
				
			}
                        
		        function jq_record_file_output(){
                                
                                var kw =$("input[name=keyword]").val();
                                var type =$("#catlist").val();
                                var form=$("<form>");//定义一个form表单
                                form.attr("style","display:none");
                                form.attr("target","");
                                form.attr("method","post");
                                form.attr("action","./?ajax=server_record_file_output&sid=1");
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

                        
	</script>
