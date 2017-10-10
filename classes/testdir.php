<?php
     $file = "/home/zcx/upload/300021/image/1001007_1495509306.jpg";
     $fileName = substr($file, strrpos($file,"/")+1);
     if(!is_dir("/var/www/html/pttweb/file/30021/video/"))
          mkdir("/var/www/html/pttweb/file/30021/video/", 0777, true);
     $targetName = "/var/www/html/pttweb/file/30021/video/".$fileName;
     if(!file_exists($targetName))
          $res = copy($file, $targetName);
?>
