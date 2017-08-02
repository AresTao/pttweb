<script>
	


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
    <li class="noRight"><a href="?page=logout">退出</a></li>
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
 <?php
   if(isset($_GET['action'])&&$_GET['action']=='show_videos'){
   ?>
<div id="urHere">手机对讲系统管理中心<b>></b><strong>视频列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3> <div style="width:10px;"></div><a href="?page=user&sid=1" class="actionBtn">返回列表</a> 视频列表</h3>

<?php
$id = SessionManager::getInstance()->getLoginId();
$srid =$_GET['sid'];
$uid = intval($_GET['uid']);
$server=MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($srid));
$videos =$server->getVideos(intval($id), $uid, 0, time());

//$chs =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($_GET['sid']))->getChannels($_GET['sid'],$_GET['cid']);

//$members =explode(",",$friendsStr);
//var_dump($videos);die;
?>

    <table width="50%" border="0" cellpadding="8" cellspacing="0" class="tableBasic" style="float:left;">
     <tr>

      <th width="20" align="center">标题</th>
      <th width="50" align="center">上传时间</th>
      

      <th width="80" align="center">操作</th>
     </tr>
	 
<?php

foreach($videos as $video){
        
        HelperFunctions::copyFile($id, $video->path,1);
        $file = $video->path;
        $fileName = substr($file, strrpos($file,"/")+1);
        $url = MUMPHPI_MAINDIR."/file/".$id."/video/".$fileName;
?>
	 
          <tr>
    
      <td align="center"><?php echo $video->title;?></td>
      <td align="center"><?php echo date("Y-m-d H:i:s", $video->time);?></td>
	  

      <td align="center">
             <a href="javascript:;" onclick="jq_video_check('<?php echo $url;?>');">查看</a>
             </td>
     </tr>



<?php
}
?>







         </table>

    <div id="photoDisplay"> 
         <video id="my-video" class="video-js" controls width="90%" height="300">
			<source src="" id="videoSrc" type="video/mp4">
			<p class="vjs-no-js">
			  To view this video please enable JavaScript, and consider upgrading to a web browser that
			  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
			</p>
		  </video>
		      </div>


    <div class="clear"></div>
	<!--
    <div class="pager">总计  个记录，共 1 页，当前第 1 页 | <a href="article.php?page=1">第一页</a> 上一页 下一页 <a href="article.php?page=1">最末页</a></div>           </div>-->

   <!-- 当前位置 -->
   <?php
    } 
     			   
	?>

	</div>

	<div id="jq_information"></div>
        <script src="http://vjs.zencdn.net/5.18.4/video.min.js"></script>	
	<script type="text/javascript">

                        function jq_video_check(url)
                        {
                            var video = document.getElementById("videoSrc");
                            video.src = url;
                            document.getElementById("my-video").load();
                            //var myPlayer = videojs('my-video');
                            //videojs("my-video").ready(function(){
                            //    var myPlayer = this;
                            //    myPlayer.play();
                            // });
                        }	
                        function jq_friend_remove(fid,uid)
			{
				$.post(
							"./?ajax=server_friend_remove&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
							{ 'sid': <?php echo $_GET['sid']; ?>, 'uid': uid,'fid':fid},
							function(data) {
								if (data.length>0) {
                                                                        window.wxc.xcConfirm("删除成功.", window.wxc.xcConfirm.typeEnum.success);
									location.href="?page=friends&sid=1&action=show_friends&uid="+uid;
								}else{
									location.href="?page=user&sid=1&action=show_members&cid="+cid;
									
									
								}
								
							}
				);
			}
                       
			
                        function jq_friend_add(uid)
			{
		            mt4Ids=[];
		            $('input[name=uid]').each(function() {
			        if(this.checked) {
			        mt4Ids.push($(this).val());
			    }
			    });
			    data = {
				mt4Ids : JSON.stringify(mt4Ids)
			    };
			
			    $.post('./?ajax=server_add_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>&uid='+uid, data, function(data){
				if(data.state == 1){
			                window.wxc.xcConfirm("添加成功.", window.wxc.xcConfirm.typeEnum.success);	
					location.href = "?page=user&sid=1&action=show_members&cid="+cid;
				}else{
					alert("操作失败");
				}
			}, 'json');	

			}	
		
                        function jq_get_location(){
                                $.post(
                                "./?ajax=server_get_location&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_videos(){
                                $.post(
                                "./?ajax=server_get_videos&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_video(){
                                $.post(
                                "./?ajax=server_get_video&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }

                        function jq_get_images(){
                                $.post(
                                "./?ajax=server_get_images&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_image(){
                                $.post(
                                "./?ajax=server_get_image&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_add_friends(){
                                $.post(
                                "./?ajax=server_add_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_get_friends(){
                                $.post(
                                "./?ajax=server_get_friends&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
                        function jq_send_message(){
                                $.post(
                                "./?ajax=server_send_message&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                                null,
                                function(data){
                                        
                                        
                                        $(".pager").html(data);
                                
                                }
                
                                )
                        }
	</script>
