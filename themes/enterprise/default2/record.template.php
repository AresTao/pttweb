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
		     url: "./?ajax=server_getRecordsByEnterprise_en&page=record&najax=1&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>",
		     success: function (data) { 
		     num =data;

		     }
		     });


                 InitTable(0);
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
                function PageCallback(index, jq) {             
                    InitTable(index); 
		    if(num<20){
						
			$(".pager").html("Total "+num+" items，totally 1 page，current page 1 </div> ");
		    }					
                }       

         }//翻页调用   
          
                //请求数据   
         function InitTable(pageIndex) {                                  
                    $.ajax({   
                        type: "POST",  
                        url: './?ajax=server_getRecordsByEnterprise_en&page=record&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
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
   if(!isset($_GET['action'])){
   ?>
<script>

</script>
<div id="urHere">Admin<b>></b><strong>Record List</strong> </div>
   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div>Record List</h3>
<form action="?page=record&action='lists'" method="post" enctype="multipart/form-data">	
       
	<input type="file" name="image" class="hidden" value=""  style="display:none"/>
</form>
  <div class="filter">
    <form action="" method="post">
    <div class="item-left">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>Search Type</label> 
     <select name="cat_id" id="catlist">
		  <option value="1">Agency ID</option>
		  <option value="2">Agency Name</option>
     </select>
     
     <input name="keyword" type="text" class="inpMain" value="" size="20" />
     </div>
     <div class="item-right">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>Start Time</label><input type="text" id="startTime" class="inpMain" name="startTime" />
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>End Time</label><input type="text" id="endTime" class="inpMain" name="endTime"/>
     </div>
     <div class="btn-item">
     <input name="submit" class="btnGray" type="submit" value="Search" onclick="jq_record_search();return false;" />
     <!--<input name="submit" class="btnGray" type="submit" value="导出为CSV文件" onclick="jq_record_file_output();return false;" />-->
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
				"./?ajax=server_record_searchByEnterprise_en&sid=1&enterpriseId=<?php echo SessionManager::getInstance()->getLoginId();?>&type=1",
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
