<script>
	
</script>
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/main.css" rel="stylesheet" type="text/css">
<link href="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/loading.css" rel="stylesheet" type="text/css">
<style type="text/css">
    </style>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=sSelQoVi2L3KofLo1HOobonW"></script>
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
    <li><a href="#" target="_blank">帮助</a></li>
    <li class="noRight"><a href="http://www.allptt.com">关于我们</a></li>
   </ul>
   <ul class="navRight">
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
  <li><a href="?page=user&sid=1"><i class="user"></i><em>用户管理</em></a></li>
  <li><a href="?page=server&sid=1"><i class="mobile"></i><em>频道管理</em></a></li>
  <li><a href="?page=monitor&sid=1"><i class="article"></i><em>监控管理</em></a></li>
  <li><a href="?page=record&sid=1"><i class="articleCat"></i><em>记录管理</em></a></li>

 </ul>

</div></div>
 <div id="dcMain">

 <div id="urHere">手机对讲系统管理中心<b>></b><strong>监控管理</strong> </div>
 <div id="contact_side">
  <div id="contact"></div>
 </div>    
    <div class="loading-container" data-bind="css: { loaded: true }">
      <div class="loading-circle" data-bind="css: { loaded: true }"></div>
    </div>
    <div id="container" style="display: none" data-bind="visible: true">
      <!-- ko with: connectDialog -->
        <div class="connect-dialog" data-bind="visible: visible">
          <div class="dialog-header">
            Connect to Server
          </div>
          <form data-bind="submit: connect">
            <table>
                <tr>
                  <td>Address</td>
                  <td><input id="address" type="text" data-bind="value: address"></td>
                </tr>
                <tr>
                  <td>Port</td>
                  <td><input id="port" type="text" data-bind="value: port"></td>
                </tr>
                <tr>
                  <td>Token</td>
                  <td><input id="token" type="text" data-bind="value: token"></td>
                </tr>
                <tr>
                  <td>Username</td>
                  <td><input id="username" type="text" data-bind="value: username"></td>
                </tr>			   
</table>
            <div class="dialog-footer">
              <input class="dialog-close" type="button" data-bind="click: hide" value="Cancel">
              <input class="dialog-submit" type="submit" value="Connect">
            </div>
          </form>
        </div>
      <!-- /ko -->
      <script type="text/html" id="user-tag">
        <span class="user-tag" data-bind="text: name"></span>
      </script>
      <script type="text/html" id="channel-tag">
        <span class="channel-tag" data-bind="text: name"></span>
      </script>
      <div class="toolbar">
        <img class="handle-horizontal" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/handle_horizontal.svg">
        <img class="handle-vertical" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/handle_horizontal.svg">
        <img class="tb-connect" data-bind="click: connectDialog.show"
                      rel="connect" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/applications-internet.svg">
        <img class="tb-information" data-bind="click: connectionInfo.show"
                      rel="information" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/information_icon.svg">
        <div class="divider"></div>
        <img class="tb-mute" data-bind="visible: !thisUser() || !thisUser().selfMute(),
                              click: function () { requestMute(thisUser) }"
                      rel="mute" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/audio-input-microphone.svg">
        <img class="tb-unmute tb-active" data-bind="visible: thisUser() && thisUser().selfMute(),
                              click: function () { requestUnmute(thisUser) }"
                      rel="unmute" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/audio-input-microphone-muted.svg">
        <img class="tb-deaf" data-bind="visible: !thisUser() || !thisUser().selfDeaf(),
                              click: function () { requestDeaf(thisUser) }"
                      rel="deaf" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/audio-output.svg">
<img class="tb-undeaf tb-active" data-bind="visible: thisUser() && thisUser().selfDeaf(),
                              click: function () { requestUndeaf(thisUser) }"
                      rel="undeaf" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/audio-output-deafened.svg">
        <img class="tb-record" data-bind="click: function(){}"
                      rel="record" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/media-record.svg">
        <div class="divider"></div>
        <img class="tb-comment" data-bind="click: commentDialog.show"
                      rel="comment" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/toolbar-comment.svg">
        <div class="divider"></div>
        <img class="tb-settings" data-bind="click: settingsDialog.show"
                      rel="settings" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/config_basic.svg">
        <div class="divider"></div>
        <img class="tb-sourcecode" data-bind="click: openSourceCode"
                      rel="Source Code" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/source-code.svg">
      </div>
      <div id="leftWrapper">
      <script type="text/html" id="channel">
        <div class="channel" data-bind="
            click: $root.select, 
            event: {
              dblclick: $root.requestMove.bind($root, $root.thisUser())
            },
            css: {
              selected: $root.selected() === $data,
              currentChannel: users.indexOf($root.thisUser()) !== -1
            }">
          <div class="channel-status">
            <img class="channel-description" data-bind="visible: description"
                alt="description" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/comment.svg">
          </div>
          <div data-bind="if: description">
<div class="channel-description tooltip" data-bind="html: description"></div>
          </div>
          <img class="channel-icon" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/channel.svg"
            data-bind="visible: !linked() && $root.thisUser().channel() !== $data">
          <img class="channel-icon-active" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/channel_active.svg"
            data-bind="visible: $root.thisUser().channel() === $data">
          <img class="channel-icon-linked" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/channel_linked.svg"
            data-bind="visible: linked() && $root.thisUser().channel() !== $data">
          <div class="channel-name" data-bind="text: name"></div>
        </div>
        <!-- ko if: expanded -->
        <!-- ko foreach: users -->
        <div class="user-wrapper">
          <div class="user-tree"></div>
          <div class="users" data-bind="
                click: $root.select,
                css: {
                  thisClient: $root.thisUser() === $data,
                  selected: $root.selected() === $data
                }">
            <div class="user-status" data-bind="attr: { title: state }">
              <img class="user-comment" data-bind="visible: comment"
                  alt="comment" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/comment.svg">
              <img class="user-server-mute" data-bind="visible: mute"
                  alt="server mute" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/muted_server.svg">
              <img class="user-suppress-mute" data-bind="visible: suppress"
                  alt="suppressed" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/muted_suppressed.svg">
              <img class="user-self-mute" data-bind="visible: selfMute" 
                  alt="self mute" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/muted_self.svg">
              <img class="user-server-deaf" data-bind="visible: deaf"
                  alt="server deaf" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/deafened_server.svg">
<img class="user-self-deaf" data-bind="visible: selfDeaf"
                  alt="self deaf" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/deafened_self.svg">
              <img class="user-authenticated" data-bind="visible: uid"
                  alt="authenticated" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/authenticated.svg">
            </div>
            <div data-bind="if: comment">
              <div class="user-comment tooltip" data-bind="html: comment"></div>
            </div>
            <img class="user-talk-off" data-bind="visible: talking() == 'off'"
                alt="talk off" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/talking_off.svg">
            <img class="user-talk-on" data-bind="visible: talking() == 'on'"
                alt="talk on" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/talking_on.svg">
            <img class="user-talk-whisper" data-bind="visible: talking() == 'whisper'"
                alt="whisper" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/talking_whisper.svg">
            <img class="user-talk-shout" data-bind="visible: talking() == 'shout'"
                alt="shout" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/talking_alt.svg">
            <div class="user-name" data-bind="text: name"></div>
          </div>
        </div>
        <!-- /ko -->
        <!-- ko foreach: channels -->
        <div class="channel-wrapper">
          <!-- ko ifnot: users().length || channels().length -->
          <div class="channel-tree"></div>
          <!-- /ko -->
          <div class="branch" data-bind="if: users().length || channels().length">
            <img class="branch-open" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/branch_open.svg"
                data-bind="click: expanded.bind($data, false), visible: expanded()">
            <img class="branch-closed" src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/svg/branch_closed.svg"
                data-bind="click: expanded.bind($data, true), visible: !expanded()">
          </div>
<div class="channel-sub" data-bind="template: {name: 'channel', data: $data}"></div>
        </div>
        <!-- /ko -->
        <!-- /ko -->
      </script>
      <div class="channel-root-container" data-bind="if: root">
        <div class="channel-root" data-bind="template: {name: 'channel', data: root}"></div>
      </div>
      <div class="chat">
        <script type="text/html" id="log-generic">
          <span data-bind="text: value"></span>
        </script>
        <script type="text/html" id="log-welcome-message">
          Welcome message: <span data-bind="html: message"></span>
        </script>
        <script type="text/html" id="log-chat-message">
          <span data-bind="visible: channel">
            (Channel)
          </span>
          <span data-bind="template: { name: 'user-tag', data: user }"></span>:
          <span class="message-content" data-bind="html: message"></span>
        </script>
        <script type="text/html" id="log-chat-message-self">
          To
          <span data-bind="template: { if: $data.channel, name: 'channel-tag', data: $data.channel }">
          </span><span data-bind="template: { if: $data.user, name: 'user-tag', data: $data.user }">
          </span>:
          <span class="message-content" data-bind="html: message"></span>
        </script>
        <script type="text/html" id="log-disconnect">
        </script>
<div class="log" data-bind="foreach: {
          data: log,
          afterRender: function (e) {
            [].forEach.call(e[1].getElementsByTagName('a'), function(e){e.target = '_blank'})
          }
        }">
          <div class="log-entry">
            <span class="log-timestamp" data-bind="text: $root.getTimeString()"></span>
            <!-- ko template: { data: $data, name: function(l) { return 'log-' + l.type; } } -->
            <!-- /ko -->
          </div>
        </div>
        <form data-bind="submit: submitMessageBox">
          <input id="message-box" type="text" data-bind="
              attr: { placeholder: messageBoxHint }, textInput: messageBox">
        </form>
      </div>
   </div>
   <!-- </div>-->
   <div id="allmap" ></div>
 <!--<iframe name="main" height="600" width="1400" src="https://voice.johni0702.de/?address=voice.johni0702.de&port=443/demo"></iframe>-->
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
<script src="<?php echo SettingsManager::getInstance()->getThemeUrl(); ?>/index.js"></script>
