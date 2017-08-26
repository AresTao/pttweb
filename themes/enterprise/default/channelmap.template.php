<script>
    
</script>
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/main.css" rel="stylesheet" type="text/css">
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/loading.css" rel="stylesheet" type="text/css">
<style type="text/css">
    </style>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=F0i6SrLmHquLVNLCqpExxPrj8mWVdFwx"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/DrawingManager_min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.css" />
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

 <div id="urHere">手机对讲系统管理中心<b>></b><strong>群组位置管理</strong><div id="fresh" style="float:right;"><button class="btn btn-primary" style="float:right;margin-right:5px;margin-top:5px;" onclick="setAutoFresh()">开启自动刷新</button></div> </div>
    <div id="leftWrapper">
        <h3>群组<?php echo $_GET['cid']?>--轨迹查询</h3>
        <div class="channelmap-root-container">
	<div>
                      <label style='font-size:15px;padding: 5px 5px 5px 2px;'>选取成员</label>
                      <select name="userId" id="userId" style="padding-left:10%;width:50%;">
<?php
        $serverId = intval($_GET['sid']);
        $entId = intval($id);
        $server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
        $usersStr = $server->getRegisteredUserIds($entId, "");
        $users = explode(",", $usersStr);
        if(count($users) > 1)
	    array_pop($users); 
?>
<?php
                                        foreach ($users AS $userId) {

                                                //FIXME Ice version check, enum-index available? otherwise, one has to edit his slice file – actually, this fixme should be a general check, in install or general warning-disableable
                                                $user = ServerInterface::getInstance()->getServerRegistration($serverId,$entId, $userId);

                                                if($user->getUserId()!==0){
?>
       <option value="<?php echo $user->getUserId(); ?>"><?php echo $user->getName(); ?></option>
 <?php
    }

}

?>

                     </select>
                      </div>
                     <div class="timeDiv">
                     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>开始时间</label><input type="text" id="startTime" class="inpMain" name="startTime" />
                     </div>
                     <div class="timeDiv">
     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>结束时间</label><input type="text" id="endTime" class="inpMain" name="endTime"/>
                     </div>
		     <button class="btn btn-primary" style="float: left;margin-top: 10px;margin-left: 10px;" type="button" onclick="showLine()">查询</button>
        </div>
        <div class="chat">
                  <h1>电子围栏设置</h1>
                  <div class="channelmap-root-container">
		   <div class="timeDiv">
                     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>开始时间</label><input type="text" id="fenceStartTime" class="inpMain" name="startTime" />
                   </div>
                   <div class="timeDiv">
		     <label style='font-size:15px;padding: 5px 5px 5px 2px;'>结束时间</label><input type="text" id="fenceEndTime" class="inpMain" name="endTime"/>
                   <input type="text" id="fenceStr" name="fenceStr" value="<?php echo $_GET['fence']?>" size="40" class="inpMain" style='display:none;'/>
                   </div>

		   <input type="button" class="btn btn-primary fenceButton" value="设置围栏" onclick="setFence(<?php echo $_GET['cid']?>)"/>    
		   <input type="button" class="btn btn-primary fenceButton" value="清除当前设置" onclick="deleteFence(<?php echo $_GET['cid']?>)"/>    
		   <input type="button" class="btn btn-primary fenceButton" value="显示围栏" onclick="showPolygon()" />    
            
		   <!-- <input type="button"  value="画点" onclick="draw(BMAP_DRAWING_MARKER)" />-->    
		   </div> 
                  
                       <h3>电子围栏信息</h3>
                           <div class="channelmap-root-container">
                   <?php if( 0 != intval($_GET['startTime'])){ ?>
		   <div id="shape"><p style="word-break:break-all;">开始时间:<?php echo date("Y-m-d H:i:s", $_GET['startTime'])?></p> 
               <p>结束时间:<?php echo date("Y-m-d H:i:s", $_GET['endTime'])?></p> 
               <p style="word-break:break-all;">围栏信息：<?php echo $_GET['fence']?></p>
               </div> 
                   <?php }else{?>
                   <div id="shape">本群组还没有设置电子围栏 </div>
                   <?php }?>
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

    var overlays = [];
    var overlaycomplete = function(e){
        overlays.push(e.overlay);
    };
    var styleOptions = {
        strokeColor:"red",    //边线颜色。
        fillColor:"red",      //填充颜色。当参数为空时，圆形将没有填充效果。
        strokeWeight: 3,       //边线的宽度，以像素为单位。
        strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
        fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
        strokeStyle: 'solid' //边线的样式，solid或dashed。
    }
    //实例化鼠标绘制工具
    var drawingManager = new BMapLib.DrawingManager(map, {
        isOpen: false, //是否开启绘制模式
        enableDrawingTool: true, //是否显示工具栏
        drawingToolOptions: {
            anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
            offset: new BMap.Size(5, 5), //偏离值
        },
        circleOptions: styleOptions, //圆的样式
        polylineOptions: styleOptions, //线的样式
        polygonOptions: styleOptions, //多边形的样式
        rectangleOptions: styleOptions //矩形的样式
    });  
	 //添加鼠标绘制工具监听事件，用于获取绘制结果
    drawingManager.addEventListener('overlaycomplete', overlaycomplete);
    function clearAll() {
		for(var i = 0; i < overlays.length; i++){
            map.removeOverlay(overlays[i]);
        }
        overlays.length = 0   
    }
    map.addEventListener("rightclick",function(e){    
        if(confirm(e.point.lng + "," + e.point.lat)){    
            $("#shape").html($("#shape").innerHTML+" <br/>("+e.point.lng+","+e.point.lat+")");    
            }    
        });

    var autoFreshId;

    function setAutoFresh()
    {

        autoFreshId = setInterval(getChannelLocations, 10000 );
        $("#fresh").html("<button class='btn btn-primary' style='float:right;margin-right:5px;margin-top:5px;' onclick='stopAutoFresh()'>取消自动刷新</button>");
    }

    function stopAutoFresh()
    {
        clearInterval(autoFreshId);
        $("#fresh").html("<button class='btn btn-primary' style='float:right;margin-right:5px;margin-top:5px;' onclick='setAutoFresh()'>开启自动刷新</button>");
    }
    function getPoint(){    
        $("#resultShape").html('');    
        for(var i = 0; i < overlays.length; i++){    
            var overlay=overlays[i].getPath();    
            $("#resultShape").val()=$("#resultShape").val()+overlay.length+'边形:<br/>';    
            for(var j = 0; j < overlay.length; j++){    
                var grid =overlay[j];    
                $("#resultShape").val()=$("#resultShape").val()+(j+1)+"个点:("+grid.lng+","+grid.lat+");<br/>";    
            }    
        }    
    }
    function  showPolygon(){
        var fence = $("#fenceStr").val();
        if (fence == "")
	    window.wxc.xcConfirm("该群组还没有设置电子围栏.", window.wxc.xcConfirm.typeEnum.success);
        var fenceArray = fence.split(";");
        var points = [];
        var maxJ=0.0,minJ=10000.0,maxW=0.0,minW=10000.0;
        for(var i=0;i<fenceArray.length-1;i++)
        {
            var fenceTwo = fenceArray[i].split(",");
            
            if(fenceTwo.length != 2) break;
            if(parseInt(fenceTwo[0]) == 0) break;
            maxJ = Math.max(maxJ, fenceTwo[0]);
            minJ = Math.min(minJ, fenceTwo[0]);
            maxW = Math.max(maxW, fenceTwo[1]);
            minW = Math.min(minW, fenceTwo[1]);

            var point = new BMap.Point(parseFloat(fenceTwo[0]), parseFloat(fenceTwo[1]));
            points.push(point);
        }    
        var zoom = getCenterPoint(maxJ,minJ,maxW,minW);
        map.centerAndZoom(new BMap.Point(zoom[0], zoom[1]), zoom[2]);

        var polygon = new BMap.Polygon(points, styleOptions);  //创建多边形    
        map.addOverlay(polygon);   //增加多边形    
        // overlays.push(polygon); //是否把该图像加入到编辑和删除行列    
    }
    function deleteFence(channelid)
    {
        clearAll();
        $.post(
                "./?ajax=server_set_channel_fence&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                {'cid':channelid, 'startTime': 0, 'endTime': 0, 'fence':"" },
                function(data){
                    if(data.length == 0)
                    {
                         window.wxc.xcConfirm("删除电子围栏成功.", window.wxc.xcConfirm.typeEnum.success);
                         $("#fenceStr").val("");
                    }
                    else
                         window.wxc.xcConfirm("删除电子围栏失败.", window.wxc.xcConfirm.typeEnum.warning);
                }
              )

    }
    function setFence(channelid)
    {
        var fenceStr="";
        if (overlays.length == 0){
             window.wxc.xcConfirm("当前地图还没有设置图形.", window.wxc.xcConfirm.typeEnum.warning);
             return;
        }
        else if(overlays.length > 1){
             window.wxc.xcConfirm("当前地图有多个图形，请清除后设置并保留一个.", window.wxc.xcConfirm.typeEnum.warning);
             return;
        }
        else{
            var overlay = overlays[0].getPath();
            var pointNum = overlay.length;
            if(pointNum > 16) pointNum = 16;
            for(var j = 0; j < pointNum; j++){
                var grid =overlay[j];
                fenceStr = fenceStr + grid.lng+","+grid.lat + ";";
            } 
        }
        var startTime = $("#fenceStartTime").val();
        var endTime = $("#fenceEndTime").val();
        if(startTime == ""|| endTime == "")
        {
             window.wxc.xcConfirm("请先设置起始时间.", window.wxc.xcConfirm.typeEnum.warning);
             return;
        }     
        var startTimestamp = Date.parse(new Date(startTime));
        startTimestamp = startTimestamp/1000;

        var endTimestamp = Date.parse(new Date(endTime));
        endTimestamp = endTimestamp/1000;

        $.post(
                "./?ajax=server_set_channel_fence&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
                {'cid':channelid, 'startTime':startTimestamp, 'endTime':endTimestamp, 'fence':fenceStr },
                function(data){
                    if(data.length == 0)
                    {
                         window.wxc.xcConfirm("设置电子围栏成功.", window.wxc.xcConfirm.typeEnum.success);
                         $("#fenceStr").val(fenceStr);
                    }
                    else
                         window.wxc.xcConfirm("设置电子围栏失败.", window.wxc.xcConfirm.typeEnum.warning);
                }
              )
    }
    
    function showLine()
    {
        var uid = $("#userId").val();

        var startTime = $("#startTime").val();
        var endTime = $("#endTime").val();

        var startTimestamp = Date.parse(new Date(startTime));
        startTimestamp = startTimestamp/1000;

        var endTimestamp = Date.parse(new Date(endTime));
        endTimestamp = endTimestamp/1000;
        if (startTime == "") startTimestamp = 0;
        if (endTime == "") endTimestamp = Date.parse(new Date())/1000;
        $.post(
                "./?ajax=server_get_location&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>",
		{'uid':uid, 'startTime':startTimestamp, 'endTime':endTimestamp},
		function(data){
		    var points = JSON.parse(data);
                    if(points.length == 0)
                         window.wxc.xcConfirm("该用户还没有上传位置信息.", window.wxc.xcConfirm.typeEnum.success);
                    else
                         displayLocation(points);
		}
	      )
    }
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

    var getChannelLocationNum = 0;
    function getChannelLocations()
    {
        getChannelLocationNum++;
        $.post(
                "./?ajax=server_get_channel_locations&sid=1&entId=<?php echo SessionManager::getInstance()->getLoginId();?>&cid=<?php echo $_GET['cid']?>","",
		function(data){
            
		    var points = JSON.parse(data);

            displayChannelLocations(points);
		}
	      )

    }
    var opts = {
        width : 50,     // 信息窗口宽度
        height: 20,      // 信息窗口高度
        title : "用户ID" // 信息窗口标题
    };
    function displayChannelLocations(pointArray)
    {
        if (document.createElement('canvas').getContext) {  // 判断当前浏览器是否支持绘制海量点
            map.clearOverlays();
            var maxJ=0.0,minJ=10000.0,maxW=0.0,minW=10000.0;
            for (var i = 0; i < pointArray.length; i++) {
                    maxJ = Math.max(maxJ, pointArray[i].lng);
                    minJ = Math.min(minJ, pointArray[i].lng);
                    maxW = Math.max(maxW, pointArray[i].lat);
                    minW = Math.min(minW, pointArray[i].lat);
                    var marker = new BMap.Marker(new BMap.Point(pointArray[i].lng,pointArray[i].lat));
                    map.addOverlay(marker);     
                    addClickHandler(pointArray[i].uid,marker);
            }
            if(pointArray.length == 0) return;
            if(getChannelLocationNum > 1) return;
            var zoom = getCenterPoint(maxJ,minJ,maxW,minW);
            map.centerAndZoom(new BMap.Point(zoom[0], zoom[1]), zoom[2]);
        } else {
            alert('请在chrome、safari、IE8+以上浏览器查看本示例');
        }
    }
    function addClickHandler(content,marker){
        marker.addEventListener("click",function(e){
            openInfo(content,e)}
            );
    }
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }
    function displayLocation(pointArray)
    {
        if (document.createElement('canvas').getContext) {  // 判断当前浏览器是否支持绘制海量点
            var points = [];  // 添加海量点数据
            var maxJ=0.0,minJ=10000.0,maxW=0.0,minW=10000.0;
	    for (var i = 0; i < pointArray.length; i++) {
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
         } else {
            alert('请在chrome、safari、IE8+以上浏览器查看本示例');
         }
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
                 $('#fenceStartTime').datetimepicker({
                     showSecond: true,
                     showMillisec: false,
                     timeFormat: 'hh:mm:ss'
                 });
                  $('#fenceEndTime').datetimepicker({
                     showSecond: true,
                     showMillisec: false,
                     timeFormat: 'hh:mm:ss'
                 });
                 getChannelLocations();
                 //setInterval(getChannelLocations, 10000 );
            
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
