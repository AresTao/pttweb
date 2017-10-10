<script>
    
</script>
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/main.css" rel="stylesheet" type="text/css">
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/loading.css" rel="stylesheet" type="text/css">
<style type="text/css">
    </style>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=F0i6SrLmHquLVNLCqpExxPrj8mWVdFwx"></script>
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
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>记录管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">

 <div id="urHere">手机对讲系统管理中心<b>></b><strong>监控管理</strong> </div>
    <div id="leftWrapper">
        <h3>用户位置查询</h3>
        <div class="channel-root-container">
             <div id="list">
<?php
        $serverId = intval($_GET['sid']);
        $entId = intval($id);
        $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
        $usersStr = $server->getRegisteredUserIds($entId, "");
	$users = explode(",", $usersStr);
        if(count($users) > 1)
	    array_pop($users); 
?>
        <div class="userList">
<?php
                                        foreach ($users AS $userId) {

                                                //FIXME Ice version check, enum-index available? otherwise, one has to edit his slice file – actually, this fixme should be a general check, in install or general warning-disableable
                                                $user = ServerInterface::getInstance()->getServerRegistration($serverId,$entId, $userId);

                                                if($user->getUserId()!==0){
?>
   <div class="userItem"><a href="javascript:;" onclick="show_locations(<?php echo $user->getUserId() ?>);"><?php echo $user->getName(); ?></a></div>
 <?php
    }

}

?>
                </div>
             </div>
        </div>
        <div class="chat">
                  <h1>推送文字到频道</h1>
                    
                  <div class="chatWrapper">
                      <div>
                      <label style='font-size:15px;padding: 5px 5px 5px 2px;'>选取频道</label>
                      <select name="channelId" id="channelId" style="padding-left:10%;width:50%;">
<?php
     $id = SessionManager::getInstance()->getLoginId();
     $sid = 1;
     $enterprise = MysqlInterface::getEnterpriseById($id);
     $data =MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($sid))->getChannels(intval($id));
     unset($data[0]);//不能使用array_shift()这样会重置数组索引
                        //var_dump($data);die;
                        //echo $curpage;

     foreach($data as $k =>$row){
	     
?>
       <option value="<?php echo $k; ?>"><?php echo $row->name; ?></option>
<?php
     }
?>
                      </select>
                      </div>
                      <div class="panel panel-primary " id="chatshow">
			   <div class="panel-heading"></div>
                           <div class="panel-body"  style="height: 80%;overflow-y:scroll;border: 1px solid #60BBFF;">
                           <ul id="panelpad" >
                           </ul>
    			   </div>
                      </div>
                      <div class="send">
                          <input type="text" class="form-control" id="messagepad">
                          <span class="input-group-btn">
                             <button class="btn btn-primary" type="button" onclick="sendmessage()">Send</button>
                          </span>
                      </div>
                  </div>
                  
                  
        </div>
    </div>
    <div id="allmap" ></div>
 <!--<iframe name="main" height="600" width="1400" src="https://voice.johni0702.de/?address=voice.johni0702.de&port=443/demo"></iframe>-->
<!--<script type="text/javascript" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/js/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");  
    //map.centerAndZoom(new BMap.Point(116.4035,39.915),8); 
    map.centerAndZoom('北京',8); 
    //setTimeout(function(){
    //    map.setZoom(14);    // setZoom 方法，负责设置级别，只有停留几秒，才能看到效果
    //}, 2000);  //2秒后放大到14级
    map.enableScrollWheelZoom(true);


    function show_locations(uid)
    {
        
        $.post(
                "./?ajax=server_get_location&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
		{'uid':uid, 'startTime':0, 'endTime': <?php echo time(); ?>},
		function(data){
		    var points = JSON.parse(data);
                    if(points.length == 0)
                         window.wxc.xcConfirm("该用户还没有上传位置信息.", window.wxc.xcConfirm.typeEnum.success);
                    else
                         displayLocation(points);
		}
	      )
    }
    function displayLocation(pointArray)
    {
        //console.log(pointArray);return;
        if (document.createElement('canvas').getContext) {  // 判断当前浏览器是否支持绘制海量点
            var points = [];  // 添加海量点数据
            var maxJ=0.0,minJ=10000.0,maxW=0.0,minW=10000.0;
	    for (var i = 1; i < pointArray.length; i++) {
                    maxJ = Math.max(maxJ, pointArray[i].longitude);
                    minJ = Math.min(minJ, pointArray[i].longitude);
                    maxW = Math.max(maxW, pointArray[i].latitude);
                    minW = Math.min(minW, pointArray[i].latitude);
		    points.push(new BMap.Point(pointArray[i].longitude, pointArray[i].latitude));
                 
	    }
            var zoom = getCenterPoint(maxJ,minJ,maxW,minW);
            map.centerAndZoom(new BMap.Point(zoom[0], zoom[1]), zoom[2]);
	    var options = {
                size: BMAP_POINT_SIZE_BIG,
                shape: BMAP_POINT_SHAPE_STAR,
                color: '#d340c3'
            }
            var pointCollection = new BMap.PointCollection(points, options);  // 初始化PointCollection
            pointCollection.addEventListener('click', function (e) {
                alert('单击点的坐标为：' + e.point.lng + ',' + e.point.lat);  // 监听点击事件
            });
            map.addOverlay(pointCollection);  // 添加Overlay
            var marker = new BMap.Marker(new BMap.Point(pointArray[0].longitude,pointArray[0].latitude));
            map.addOverlay(marker);
            var dateItem = new Date();
            dateItem.setTime(pointArray[pointArray.length-1].time* 1000);
            var dateStr = dateItem.toLocaleDateString();
            addClickHandler("时间："+dateStr, marker);
         } else {
            alert('请在chrome、safari、IE8+以上浏览器查看本示例');
         }
    }
    var opts = {
        width : 50,     // 信息窗口宽度
        height: 40,      // 信息窗口高度
        title : "位置信息" // 信息窗口标题
    };
    function addClickHandler(content,marker){
        marker.addEventListener("click",function(e){
                openInfo(content,e)}
                );
    }
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content+"，经度："+p.getPosition().lng+"，纬度："+p.getPosition().lat,opts);  
        // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }
    function sendmessage()
    {
        var cid = $('#channelId').val();
        var message = $('#messagepad').val();
        
        $.post(
		"./?ajax=server_send_message&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
		{ 'cid':cid, 'message': message },
		function(data){

		}
                
	 )
        var ele =  '<li><p>'+'To channel '+cid + ':'+ message + ' </p></li>';
        $('#panelpad').append(ele);
        $('#panelpad').children('li')[$('#panelpad').find('li').length - 1].scrollIntoView();
    }
    function scrollToBottom() {
        window.scrollTo(0, $("#panelpad").clientHeight);
    }
    function getRoom(diff){  
        var room =    new Array(0,  1,  2, 3, 4, 5, 6,7,8,  9,   10,  11,  12,  13, 14);  
        var diffArr = new Array(360,180,90,45,22,11,5,2.5,1.25,0.6,0.3,0.15,0.07,0.03,0);  
        for(var i = 0; i < diffArr.length; i ++){  
            if((diff - diffArr[i]) >= 0){  
                return room[i];  
            }  
        }     
        return 14;  
    }
 
    function getCenterPoint(maxJ,minJ,maxW,minW){//通过经纬度获取中心位置和缩放级别  
	    if(maxJ==minJ&&maxW==minW)return [maxJ,maxW,0];  
	    var diff = maxJ - minJ;  
	    if(diff < (maxW - minW))diff = maxW - minW;  
	    diff = parseInt(10000 * diff)/10000;      
	    var centerJ = minJ*1000000+1000000*(maxJ - minJ)/2;  
	    var centerW = minW*1000000+1000000*(maxW - minW)/2;  
	    var zoom = getRoom(diff);  
	    return [centerJ/1000000,centerW/1000000,zoom];  
    }

    
    $(function(){
    var $triggerBox = $('[data-role=cont-trigger-box]'),
        $trigger = $triggerBox.find('[data-role=cont-trigger]'),
        $contBox = $('[data-role=cont-box]'),
        $contItem = $contBox.find('[data-role=cont-item]');

    $trigger.on('click', function(){
    	
        var targetIndex = $trigger.index($(this));
        $trigger.removeClass('activediv').eq(targetIndex).addClass('activediv');
        $contItem.removeClass('active').eq(targetIndex).addClass('active');
    });
});

 
    var winHeight=0;
 
    function findDimensions() {
 
        if (window.innerHeight) {
            winHeight = window.innerHeight;
        }
        if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) {
            winHeight=document.documentElement.clientHeight;
            winWidth=document.documentElement.clientWidth;
        }
 
        if (document.getElementById("leftWrapper")) {
 
            document.getElementById("leftWrapper").style.height = (winHeight-75) + "px";
 
        }
        if (document.getElementById("allmap"))
        {
            document.getElementById("allmap").style.height = (winHeight-75) + "px";
        }
 
    }
 
    findDimensions();
 
    window.onresize=findDimensions;
</script>
