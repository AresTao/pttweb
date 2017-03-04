<script>
	
</script>
<style type="text/css">
        #allmap{width:100%;height:500px;}
    </style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=sSelQoVi2L3KofLo1HOobonW"></script>
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
  <li><a href="?page=user&sid=1"><i class="article"></i><em>用户管理</em></a></li>
  <li><a href="?page=server&sid=1"><i class="articleCat"></i><em>频道管理</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="articleCat"></i><em>监控管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">

 <div id="urHere">手机对讲系统管理中心<b>></b><strong>监控管理</strong> </div>
     
			   
 <div id="allmap"></div>
<script type="text/javascript">
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

    
    // 百度地图API功能
    var map = new BMap.Map("allmap");  
    //map.centerAndZoom(new BMap.Point(116.4035,39.915),8); 
    map.centerAndZoom('宿迁',8); 
    setTimeout(function(){
        map.setZoom(14);    // setZoom 方法，负责设置级别，只有停留几秒，才能看到效果
    }, 2000);  //2秒后放大到14级
    map.enableScrollWheelZoom(true);
</script>
