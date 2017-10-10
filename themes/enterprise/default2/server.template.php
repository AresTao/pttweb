
<script>
	
	var num;	
	var pageIndex = 0;     //页面索引初始值   
	var pageSize = 10;     //每页显示条数初始化，修改显示条数，修改这里即可   	
 $(function () {
		$.ajax
       ({
           cache: false,
           async: false,
           type: 'post',
           data: { sid: "1" },
		   dataType: "json",
           url: "./?ajax=show_tree&najax=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
           success: function (data) { 
				num =data;
				
           }
       });
              InitTable(0);    //Load事件，初始化表格数据，页面索引为0（第一页）
	
				
                //分页，PageCount是总条目数，这是必选参数，其它参数都是可选
                $(".pager").pagination(num,{
                    callback: PageCallback,  //PageCallback() 为翻页调用次函数。
                    prev_text: "« 上一页",
                    next_text: "下一页 »",
                    items_per_page:pageSize,
                    num_edge_entries: 2,       //两侧首尾分页条目数
                    num_display_entries: 6,    //连续分页主体部分分页条目数
                    current_page: pageIndex,   //当前页索引
					total:num
                });
                //翻页调用   
                function PageCallback(index, jq) {             
                    InitTable(index);  
                }  
                //请求数据   
                function InitTable(pageIndex) {                                  
                    $.ajax({   
                        type: "POST",  
                        url: './?ajax=show_tree&entId=<?php echo SessionManager::getInstance()->getLoginId();?>',      //提交到一般处理程序请求数据   
                        data: "pageIndex=" + (pageIndex+1) + "&pageSize=" + pageSize+"&sid=1",          //提交两个参数：pageIndex(页面索引)，pageSize(显示条数)                   
                        success: function(data) {
                                 
							
                            $("#list").html(data);             //将返回的数据追加到表格   
						
							
                        }  
                    }); 
                }
            }); 

		


</script>
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
   <li class="noRight"><a href="#"> 企业编号：<?php echo $enterprise['id'];?></a></li>
        <li class="noRight"><a href="#"> 企业名称：<?php echo $enterprise['name'];?> </a> </li>
   </ul>
   <ul class="navRight">

    <li class="noLeft"><a href="#">可用永久卡：<?php echo $enterprise['availablePCards'];?></a></li>
    <li class="noLeft"><a href="#">可用年卡：<?php echo $enterprise['availableCards'];?></a></li>
    <li class="M noLeft"><a href="JavaScript:void(0);">您好，<?php echo SessionManager::getInstance()->getLoginName();?></a>
     <div class="drop mUser">
            <a href="?page=admins&sid=1">编辑我的个人资料</a>
     </div>
    </li>
    <li class="noRight"><a href="./?page=logout">退出</a></li>
   </ul>
  </div>
 </div>
</div>
<!-- dcHead 结束 --> <div id="dcLeft"><div id="menu">

  <ul>
  <li><a href="?page=user&sid=1"><i class="user"></i><em>用户管理</em></a></li>
  <li><a href="?page=server&sid=1"><i class="mobile"></i><em>频道管理</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="article"></i><em>监控管理</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>交易记录</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">
   <!-- 当前位置 -->
<div id="urHere">管理中心<b>></b><strong>频道管理</strong> </div>  

 <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;" >
        
    	<h3><a href="?page=channel&action=add&sid=1" class="actionBtn add">添加频道</a>频道管理</h3>
		   <div id="list">
		 </div>
		  	  <div class="clear"></div>
    <div class="pager"></div>    
	</div>

           </div>

 </div>





	<script type="text/javascript">
		/*<![CDATA[*/

			function jq_server_setSuperuserPassword(sid)
			{
				$('#li_server_superuserpassword > .ajax_info').html(imgAjaxLoading);
				var pw = randomString(6);
				$.post('./?ajax=server_setSuperuserPassword',
						{ 'sid':1, 'pw': pw },
						function (data) {
							if (data=='') {
								$('#li_server_superuserpassword > .ajax_info').html('<div>Password set to: '+pw+'</div>');
							} else {
								$('#li_server_superuserpassword > .ajax_info').html(data);
							}
						}
					);
			}
		
			function jq_server_registration_remove(uid)
			{
				$.post(
							"./?ajax=server_regstration_remove",
							{ 'sid': 1, 'uid': uid },
							function(data) {
								if (data.length>0) {
									alert('failed: '+data);
								}
								jq_server_getRegistrations(<?php echo $_GET['sid']; ?>);
							}
				);
			}
			
		function jq_server_channel_remove(aid)
			{
				$.post(
							"./?ajax=server_channel_remove&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid':1, 'aid': aid },
							function(data) {
								if (data.length>0) {
                                                                    window.wxc.xcConfirm("删除成功.", window.wxc.xcConfirm.typeEnum.success);
								}
								location.href="?page=server&sid=1";
							
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
		
			
			//$('#jq_information').show().html($(parent).id());
		/*]]>*/
	</script>

