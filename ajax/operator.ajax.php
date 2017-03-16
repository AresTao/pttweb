<?php
/**
 * Ajax functionality
 * @author Kissaki
 */

require_once dirname(__FILE__).'/ajax.ajax.php';

/**
 * ajax functionality, functions for the admin section
 * @author Kissaki
 */
class Ajax_Operator extends Ajax
{
	public static function getPage()
	{
		TemplateManager::parseTemplate($_GET['page']);
	}

        public static function server_operator_add(){
                $sid =$_GET['sid'];
                $parentId =$_GET['parentId'];
                $account=$_POST['account'];
                $passwd=$_POST['passwd'];
                $name=$_POST['name'];
                $comment=$_POST['comment'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $previlege='r,w';
                $type=2;
		$res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::addOperator($parentId,$type,$account,$passwd,$name, $comment, $email,$phone,$previlege);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }

        public static function server_operator_update(){
                $id =$_GET['id'];
                $passwd=$_POST['passwd'];
                if ($passwd == '')
                     $passwd = null;
                $name=$_POST['name'];
                $comment=$_POST['comment'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
		$res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::updateOperator($id,null,$passwd, $name,$comment,$email,$phone,null);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_operator_remove(){
                $id =$_GET['id'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::deleteOperatorById($id);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
	public static function server_getOperators()
	{
		//$serverId = intval($_POST['sid']);
		//if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
	        if(!isset($_GET['najax'])){
		       try {
		       $pageIndex =intval($_POST['pageIndex'])-1;
		       $pageSize =intval($_POST['pageSize']);
                        $parentId = $_GET['parentId'];
		       //echo $pageSize;die;
			$curpage = $pageIndex*$pageSize;
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			//$users = $server->getRegisteredUsers();
			$users = MysqlInterface::getOperatorsByParentId($parentId);
			
			//unset($users[0]);//不能使用array_shift()这样会重置数组索引
			//var_dump($users);
			//echo $curpage;
			$users =array_slice($users,$curpage,$pageSize,true);
			
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="20" align="center">姓名</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="20" align="center">年卡数</th>
      <th width="80" align="center">操作</th>
      </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>
     
      <td align="center">
             <a href="./?page=operator&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | <a href="./?page=operator&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;
" onclick="if(confirm('确定删除用户?')){jq_operator_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		        } catch(Exception $exc) {
			        echo '<div class="error">Server is not running</div>';
		        }
	       }else{
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
                        $parentId = $_GET['parentId'];
			$users = MysqlInterface::getOperatorsByParentId($parentId);	
			//array_shift($users);
			$i =count($users);
			echo json_encode($i);
	       } 		
	}
        public static function server_operator_search()
        {
                $value =addslashes($_POST['value']);
                //echo $kw;
                $type =intval($_POST['type']);
                $parentId =intval($_GET['parentId']);
                $users = MysqlInterface::searchOperators($type, $value, $parentId);
                $total = count($users);
?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户号</th>
      <th width="20" align="center">账号</th>
      <th width="20" align="center">姓名</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="20" align="center">年卡数</th>
      <th width="80" align="center">操作</th>
      </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>
     
      <td align="center">
             <a href="./?page=operator&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | <a href="./?page=operator&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> | <a href="javascript:;
" onclick="if(confirm('确定删除用户?')){jq_operator_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               
        }
        
        
        public static function server_enterprise_add(){
                $sid =$_GET['sid'];
                $parentId =$_GET['uid'];
                $account=$_POST['account'];
                $passwd=$_POST['passwd'];
                $name=$_POST['name'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $comment=$_POST['comment'];
                $previlege='r,w';
                $type=1;
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::addEnterprise($parentId,$type,$account,$passwd,$name,$email,$phone,$comment,$previlege);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_enterprise_update(){
                $id =$_GET['id'];
                $passwd=$_POST['passwd'];
                if ($passwd == '')
                    $passwd = null;
                $name=$_POST['name'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $comment=$_POST['comment'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::updateEnterprise($id,null,$passwd, $name,$email,$phone,$comment,null);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_enterprise_remove(){
                $id =$_GET['id'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::deleteEnterpriseById($id);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
	public static function server_getEnterprises()
	{
		//$serverId = intval($_POST['sid']);
		//if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
	        if(!isset($_GET['najax'])){
		       try {
		       $pageIndex =intval($_POST['pageIndex'])-1;
		       $pageSize =intval($_POST['pageSize']);
                       
		       $parentId = $_GET['parentId'];
		       //echo $pageSize;die;
		       $curpage = $pageIndex*$pageSize;
		       //$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
		       //$users = $server->getRegisteredUsers();
		       $users = MysqlInterface::getEnterprisesByParentId($parentId);

		       //	unset($users[0]);//不能使用array_shift()这样会重置数组索引
		       //		var_dump($users);
		       //echo $curpage;
		       $users =array_slice($users,$curpage,$pageSize,true);

		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户编号</th>
      <th width="20" align="center">账号</th>
      <th width="60" align="center">联系人</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">可用年卡数</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>


      <td align="center">
             <?php 
                 if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 1){ 
             ?>
             <a href="./?page=enterprise1&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | 
             <a href="./?page=enterprise1&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> 
             <?php
                 }else if (SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 2){
             ?>
             <a href="./?page=enterprise2&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | 
             <a href="./?page=enterprise2&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> 
             <?php 
                 }
             ?>
             
             | <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_enterprise_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		        } catch(Exception $exc) {
			        echo '<div class="error">Server is not running</div>';
		        }
	       }else{
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
                        $parentId = $_GET['parentId'];
			$users = MysqlInterface::getEnterprisesByParentId($parentId);	
			//array_shift($users);
			$i =count($users);
			echo json_encode($i);
	       } 		
	}
        public static function server_enterprise_search()
        {
                $value =addslashes($_POST['value']);
                //echo $kw;
                $type =intval($_POST['type']);
                $parentId =intval($_GET['parentId']);
                $users = MysqlInterface::searchEnterprisesByParentId($type, $value,$parentId);
                $total = count($users);
?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">用户编号</th>
      <th width="20" align="center">账号</th>
      <th width="20" align="center">联系人</th>
      <th width="60" align="center">邮箱</th>
      <th width="60" align="center">电话</th>
      <th width="60" align="center">备注</th>
      <th width="60" align="center">可用年卡数</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($users AS $member) {
						
?>
<tr>

      <td align="center"><?php echo $member['id'];?></td>
      <td align="center"><?php echo $member['account'];?></td>
      <td align="center"><?php echo $member['name'];?></td>
      <td align="center"><?php echo $member['email'];?></td>
      <td align="center"><?php echo $member['phone'];?></td>
      <td align="center"><?php echo $member['comment'];?></td>
      <td align="center"><?php echo $member['availableCards'];?></td>


      <td align="center">
             <?php 
                 if(SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 1){ 
             ?>
             <a href="./?page=enterprise1&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | 
             <a href="./?page=enterprise1&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> 
             <?php
                 }else if (SessionManager::getInstance()->isOperator() && SessionManager::getInstance()->getLevel() == 2){
             ?>
             <a href="./?page=enterprise2&sid=1&action=dispatcher&id=<?php echo $member['id'] ?>" >分配</a> | 
             <a href="./?page=enterprise2&sid=1&action=edit&id=<?php echo $member['id'] ?>" >编辑</a> 
             <?php 
                 }
             ?>

             | <a href="javascript:;" onclick="if(confirm('确定删除用户?')){jq_enterprise_remove(<?php echo $member['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               
        }

        public static function batch_add_enterprise()
        {
                $res = array();
                
		if ($_FILES["fileToUpload"]["error"] > 0)
		{
                        $res['code']  = 400;
			$res['reason'] = $_FILES["fileToUpload"]["error"];
                        echo json_encode($res);
                        return;
		}
                $parentId = $_GET['uid'];
		$file_types = explode ( ".", $_FILES ['fileToUpload']['name'] );
		$file_type = $file_types[count ($file_types) - 1];
		$SUPPORT_TYPE = array('csv','xls','xlsx');
		if (!in_array($file_type,$SUPPORT_TYPE))
		{
			$res['code']  = 400;
			$res['reason'] = "unsupport document type.";
			echo json_encode($res);
			return;
		}
                $PHPExcel=null;
                if ($file_type == 'xls')
                {
		        $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		        $PHPExcel = $reader->load($_FILES["fileToUpload"]["tmp_name"]); // 载入excel文件
                }else if ($file_type == 'xlsx')
                {
                        $PHPExcel = PHPExcel_IOFactory::load($_FILES["fileToUpload"]["tmp_name"]);
                }
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
               
                $code = 200;
                $reason = '';
		/** 循环读取每个单元格的数据 */
		for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
			$name = $sheet->getCell("A".$row)->getValue();
                        $passwd = $sheet->getCell("B".$row)->getValue();
                        if ($name == '' || $passwd == '')    continue;
                        $email = $sheet->getCell("C".$row)->getValue();
                        $phone = $sheet->getCell("D".$row)->getValue();
                        $comment = $sheet->getCell("E".$row)->getValue();
                        $previlege='r,w';
			$type=2;
                        
                        $result = MysqlInterface::addEnterprise($parentId,$type,$name,$passwd,$email,$phone,$comment,$previlege);
		        if ($result <= 0)
                        {
                             $code = 400;
                             $reason = $reason." | 文件行 ".$row." 添加失败";
                        }
                }
                $res['code'] = $code;
                $res['reason'] = $reason;
                echo json_encode($res);
                
        }

        public static function server_enterprise_file_output()
        {
		$value =addslashes($_POST['value']);
		$type =intval($_POST['type']);
		$users = MysqlInterface::searchEnterprises($type, $value);                 
		$total = count($users);

                $fp = fopen('php://output', 'a');

                $head = array('账号', '密码', '邮箱', '电话', '备注');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
                
                // 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		// 计数器
		$cnt = 0;
		// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
		$limit = 100000;

                foreach($users as $row)
                {
			$cnt ++;
			if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
				ob_flush();
				flush();
				$cnt = 0;
			}

			foreach ($row as $i => $v) {
				$row[$i] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $row);
                }
                // Create new PHPExcel object
		/*$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("allptt")
			->setLastModifiedBy("allptt")
			->setTitle("企业用户表")
			->setSubject("")
			->setDescription("企业用户表")
			->setKeywords("企业 用户")
			->setCategory("表单");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Hello')
			->setCellValue('B2', 'world!')
			->setCellValue('C1', 'Hello')
			->setCellValue('D2', 'world!');

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A4', 'Miscellaneous glyphs')
			->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç')
			->setCellValue('A6', '吴占涛');

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('企业用户');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
*/
               // ob_end_clean(); 
                $filename = "Enterprise";
		// Redirect output to a client’s web browser (Excel2007)
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="01simple.xlsx"');
		//header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		//header('Cache-Control: max-age=1');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='.$filename.'.csv');
                header('Cache-Control: max-age=0');
		// If you're serving to IE over SSL, then the following may be needed
		//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		//header ('Pragma: public'); // HTTP/1.0

		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save('php://output');
		exit;

	}
        public static function batch_add_operator()
        {
                $res = array();
                
		if ($_FILES["fileToUpload"]["error"] > 0)
		{
                        $res['code']  = 400;
			$res['reason'] = $_FILES["fileToUpload"]["error"];
                        echo json_encode($res);
                        return;
		}
                $parentId = $_GET['uid'];
		$file_types = explode ( ".", $_FILES ['fileToUpload']['name'] );
		$file_type = $file_types[count ($file_types) - 1];
		$SUPPORT_TYPE = array('csv','xls','xlsx');
		if (!in_array($file_type,$SUPPORT_TYPE))
		{
			$res['code']  = 400;
			$res['reason'] = "unsupport document type.";
			echo json_encode($res);
			return;
		}
                $PHPExcel=null;
                if ($file_type == 'xls')
                {
		        $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		        $PHPExcel = $reader->load($_FILES["fileToUpload"]["tmp_name"]); // 载入excel文件
                }else if ($file_type == 'xlsx')
                {
                        $PHPExcel = PHPExcel_IOFactory::load($_FILES["fileToUpload"]["tmp_name"]);
                }
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
               
                $code = 200;
                $reason = '';
		/** 循环读取每个单元格的数据 */
		for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
			$name = $sheet->getCell("A".$row)->getValue();
                        $passwd = $sheet->getCell("B".$row)->getValue();
                        if ($name == '' || $passwd == '')    continue;
                        $email = $sheet->getCell("C".$row)->getValue();
                        $phone = $sheet->getCell("D".$row)->getValue();
                        $comment = $sheet->getCell("E".$row)->getValue();
                        $previlege='r,w';
			$type=2;
                        
                        $result = MysqlInterface::addOperator($parentId,$type,$name,$passwd,$email,$phone,$comment,$previlege);
		        if ($result <= 0)
                        {
                             $code = 400;
                             $reason = $reason." | 文件行 ".$row." 添加失败";
                        }
                }
                $res['code'] = $code;
                $res['reason'] = $reason;
                echo json_encode($res);
                
        }

        public static function server_dispatcher_add()
        {
                $parentId = intval($_GET['parentId']);
                $operatorId = $_POST['operatorId'];
                $toType = 2;
                $cardNum = intval($_POST['cardNum']);
                $availableCards =intval( $_POST['availableCards'] );
                //$availableGroups = intval($_POST['availableGroups']);
                //$groupNum =intval( $_POST['groupNum']);
                $cost = $_POST['cost'];
                $fromId = $parentId;
                $fromType = 2;
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        $res = MysqlInterface::checkIfOperatorCanDispatcher($fromId, $cardNum);
                        if ($res)
                        {

				$res = MysqlInterface::addOperatorToOperatorDispatcher($fromId,$cardNum,$operatorId,$cardNum+$availableCards);
				$res = MysqlInterface::addRecord($fromId,$fromType,$operatorId, $toType,$cardNum,$cost);

                        } else {
                                throw new Exception("年卡数量不足，请充卡");
                        }
                } catch(Exception $exc) {
                        echo $exc->getMessage();
                        return ;
                }
		echo "succeed!";

        }
        public static function server_dispatcher_enterprise_add()
        {
                $parentId = intval($_GET['parentId']);
                $enterpriseId = $_POST['enterpriseId'];
                $toType = 3;
                $cardNum = intval($_POST['cardNum']);
                $availableCards =intval( $_POST['availableCards'] );
                //$availableGroups = intval($_POST['availableGroups']);
                //$groupNum =intval( $_POST['groupNum']);
                $cost = $_POST['cost'];
                $fromId = $parentId;
                $fromType = 2;
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        $res = MysqlInterface::checkIfOperatorCanDispatcher($parentId, $cardNum);
                        if ($res)
                        {
                                
                                $res = MysqlInterface::addOperatorToEnterpriseDispatcher($parentId,$cardNum, $enterpriseId,$cardNum+$availableCards);
                                $res = MysqlInterface::addRecord($fromId,$fromType,$enterpriseId, $toType,$cardNum,$cost);
                        }else
                        {
                                throw new Exception("年卡数量不足，请充卡");
                        }
                } catch(Exception $exc) {
                        echo $exc->getMessage();
                        return;
                }
		echo "succeed!";

        }


        public static function server_operator_file_output()
        {
		$value =addslashes($_POST['value']);
		$type =intval($_POST['type']);
                $parentId =intval($_GET['parentId']);
		$users = MysqlInterface::searchOperatorsByParentId($type, $value, $parentId);                 
		$total = count($users);

                $fp = fopen('php://output', 'a');

                $head = array('账号', '密码', '邮箱', '电话', '备注');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
                
                // 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		// 计数器
		$cnt = 0;
		// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
		$limit = 100000;

                foreach($users as $row)
                {
			$cnt ++;
			if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
				ob_flush();
				flush();
				$cnt = 0;
			}

			foreach ($row as $i => $v) {
				$row[$i] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $row);
                }
                // Create new PHPExcel object
		/*$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("allptt")
			->setLastModifiedBy("allptt")
			->setTitle("企业用户表")
			->setSubject("")
			->setDescription("企业用户表")
			->setKeywords("企业 用户")
			->setCategory("表单");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Hello')
			->setCellValue('B2', 'world!')
			->setCellValue('C1', 'Hello')
			->setCellValue('D2', 'world!');

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A4', 'Miscellaneous glyphs')
			->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç')
			->setCellValue('A6', '吴占涛');

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('企业用户');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
*/
               // ob_end_clean(); 
                $filename = "代理商名单";
		// Redirect output to a client’s web browser (Excel2007)
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="01simple.xlsx"');
		//header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		//header('Cache-Control: max-age=1');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='.$filename.'.csv');
                header('Cache-Control: max-age=0');
		// If you're serving to IE over SSL, then the following may be needed
		//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		//header ('Pragma: public'); // HTTP/1.0

		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save('php://output');
		exit;

	}

        public static function server_bill_add(){
                $sid =$_GET['sid'];
                $parentId =$_GET['uid'];
                $operatorId=$_POST['operatorId'];
                $allCards=$_POST['number'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::addBill($operatorId,$operatorId,$allCards);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_bill_update(){
                $id =$_GET['id'];
                $uname=$_POST['uname'];
                $passwd=$_POST['passwd'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
                $comment=$_POST['comment'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::updateEnterprise($id,null,$uname,$passwd,$email,$phone,$comment,null);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
        public static function server_bill_remove(){
                $id =$_GET['id'];
                $res;
                //if (!PermissionManager::getInstance()->serverCanEditAdmins())
                //        return ;
                try {
                        
                        $res = MysqlInterface::deleteEnterpriseById($id);

                } catch(Exception $exc) {

                }
                if ($res > 0)
                    echo "succeed!";
        }
	public static function server_getBills()
	{
		//$serverId = intval($_POST['sid']);
		//if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
		//	echo tr('permission_denied');
		//	MessageManager::echoAllMessages();
		//	exit();
		//}
	        if(!isset($_GET['najax'])){
		       try {
		       $pageIndex =intval($_POST['pageIndex'])-1;
		       $pageSize =intval($_POST['pageSize']);
		       //echo $pageSize;die;
			$curpage = $pageIndex*$pageSize;
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			//$users = $server->getRegisteredUsers();
			$bills = MysqlInterface::getBills();
			
		//	unset($users[0]);//不能使用array_shift()这样会重置数组索引
	        //		var_dump($users);
			//echo $curpage;
			$bills =array_slice($bills,$curpage,$pageSize,true);
			
		
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">账单号</th>
      <th width="20" align="center">代理商</th>
      <th width="60" align="center">企业</th>
      <th width="60" align="center">已用数</th>
      <th width="60" align="center">总数</th>
      <th width="60" align="center">状态</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($bills AS $bill) {
						
?>
<tr>

      <td align="center"><?php echo $bill['id'];?></td>
      <td align="center"><?php echo $bill['operatorId'];?></td>
      <td align="center"><?php echo $bill['enterpriseId'];?></td>
      <td align="center"><?php echo $bill['usedCards'];?></td>
      <td align="center"><?php echo $bill['allCards'];?></td>
      <td align="center"><?php echo $bill['status'];?></td>


      <td align="center">
             <a href="./?page=bill&sid=1&action=edit&id=<?php echo $bill['id'] ?>" >编辑</a> | <a href="javascript:;" onclick="if(confirm('确定删除账单?')){jq_bill_remove(<?php echo $bill['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="pager"></div>	
<?php
		        } catch(Exception $exc) {
			        echo '<div class="error">Server is not running</div>';
		        }
	       }else{
			//$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
			$bills = MysqlInterface::getBills();	
			//array_shift($users);
			$i =count($bills);
			echo json_encode($i);
	       } 		
	}
        public static function server_bill_search()
        {
                $value =addslashes($_POST['value']);
                //echo $kw;
                $type =intval($_POST['type']);
                $users = MysqlInterface::searchBills($type, $value);
                $total = count($users);
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">账单号</th>
      <th width="20" align="center">代理商</th>
      <th width="60" align="center">企业</th>
      <th width="60" align="center">已用数</th>
      <th width="60" align="center">总数</th>
      <th width="60" align="center">状态</th>
      <th width="80" align="center">操作</th>
     </tr>       
<?php
    foreach ($bills AS $bill) {
						
?>
<tr>

      <td align="center"><?php echo $bill['id'];?></td>
      <td align="center"><?php echo $bill['operatorId'];?></td>
      <td align="center"><?php echo $bill['enterpriseId'];?></td>
      <td align="center"><?php echo $bill['usedCards'];?></td>
      <td align="center"><?php echo $bill['allCards'];?></td>
      <td align="center"><?php echo $bill['status'];?></td>


      <td align="center">
             <a href="./?page=bill&sid=1&action=edit&id=<?php echo $bill['id'] ?>" >编辑</a> | <a href="javascript:;" onclick="if(confirm('确定删除账单?')){jq_bill_remove(<?php echo $bill['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
		<div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>	

<?php               
        
       }

        public static function server_bill_file_output()
        {
		$value =addslashes($_POST['value']);
		$type =intval($_POST['type']);
		$bills = MysqlInterface::searchBills($type, $value);                 
		$total = count($bills);

                $fp = fopen('php://output', 'a');

                $head = array('账号', '密码', '邮箱', '电话', '备注');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
                
                // 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		// 计数器
		$cnt = 0;
		// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
		$limit = 100000;

                foreach($bills as $row)
                {
			$cnt ++;
			if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
				ob_flush();
				flush();
				$cnt = 0;
			}

			foreach ($row as $i => $v) {
				$row[$i] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $row);
                }
                // Create new PHPExcel object
		/*$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("allptt")
			->setLastModifiedBy("allptt")
			->setTitle("企业用户表")
			->setSubject("")
			->setDescription("企业用户表")
			->setKeywords("企业 用户")
			->setCategory("表单");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Hello')
			->setCellValue('B2', 'world!')
			->setCellValue('C1', 'Hello')
			->setCellValue('D2', 'world!');

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A4', 'Miscellaneous glyphs')
			->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç')
			->setCellValue('A6', '吴占涛');

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('企业用户');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
*/
               // ob_end_clean(); 
                $filename = "账单名单";
		// Redirect output to a client’s web browser (Excel2007)
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="01simple.xlsx"');
		//header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		//header('Cache-Control: max-age=1');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='.$filename.'.csv');
                header('Cache-Control: max-age=0');
		// If you're serving to IE over SSL, then the following may be needed
		//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		//header ('Pragma: public'); // HTTP/1.0

		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		//$objWriter->save('php://output');
		exit;

	}

        public static function server_getRecords()
        {
                //$serverId = intval($_POST['sid']);
                //if (!PermissionManager::getInstance()->serverCanViewRegistrations($serverId)) {
                //      echo tr('permission_denied');
                //      MessageManager::echoAllMessages();
                //      exit();
                //}
                if(!isset($_GET['najax'])){
                       try {
                       $pageIndex =intval($_POST['pageIndex'])-1;
                       $pageSize =intval($_POST['pageSize']);
                       //echo $pageSize;die;
                        $curpage = $pageIndex*$pageSize;
                        //$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
                        //$users = $server->getRegisteredUsers();
                        $records = MysqlInterface::getRecordsByAdmin();

                //      unset($users[0]);//不能使用array_shift()这样会重置数组索引
                //              var_dump($users);
                        //echo $curpage;
                        $records =array_slice($records,$curpage,$pageSize,true);


?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>

      <th width="20" align="center">记录号</th>
      <th width="30" align="center">代理商编号</th>
      <th width="30" align="center">代理商姓名</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
      <th width="80" align="center">操作</th>
     </tr>
<?php
    foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['toId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>


      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除记录?')){jq_record_remove(<?php echo $record['id'] ?>);}">删除</a>
             </td>
     </tr>

<?php
}


?>

</table>
                <div class="clear"></div>
<div class="pager"></div>
<?php
                        } catch(Exception $exc) {
                                echo '<div class="error">Server is not running</div>';
                        }
               }else{
                        //$server = MurmurServer::fromIceObject(ServerInterface::getInstance()->getServer($serverId));
                        $records = MysqlInterface::getRecordsByAdmin();
                        //array_shift($users);
                        $i =count($records);
                        echo json_encode($i);
               }
        }
        public static function server_record_search()
        {
                $value =addslashes($_POST['value']);
                //echo $kw;
                $type =intval($_POST['type']);
                $records = MysqlInterface::searchRecordsByAdmin($type, $value);
                $total = count($records);
?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="20" align="center">记录号</th>
      <th width="30" align="center">代理商编号</th>
      <th width="30" align="center">代理商姓名</th>
      <th width="30" align="center">年卡数</th>
      <th width="30" align="center">金额</th>
      <th width="60" align="center">操作时间</th>
      <th width="80" align="center">操作</th>
     </tr>
<?php
    foreach ($records AS $record) {

?>
<tr>

      <td align="center"><?php echo $record['id'];?></td>
      <td align="center"><?php echo $record['toId'];?></td>
      <td align="center"><?php echo $record['name'];?></td>
      <td align="center"><?php echo $record['cardNum'];?></td>
      <td align="center"><?php echo $record['cost'];?></td>
      <td align="center"><?php echo $record['createTime'];?></td>
      <td align="center">
             <a href="javascript:;" onclick="if(confirm('确定删除账单?')){jq_bill_remove(<?php echo $record['id'] ?>);}">>删除</a>
             </td>
     </tr>

<?php
}


?>

        </table>
                <div class="clear"></div>
<div class="page" style="text-align: right;padding-top: 20px;"><?php echo "总计".$total."个记录，共 1 页，当前第 1 页"?></div>
<?php

       }

        public static function server_record_file_output()
        {
                $value =addslashes($_POST['value']);
                $type =intval($_POST['type']);
                $records = MysqlInterface::searchRecordsByAdmin($type, $value);
                $total = count($records);

                $fp = fopen('php://output', 'a');

                $head = array('账号', '密码', '邮箱', '电话', '备注');
                foreach ($head as $i => $v) {
                        // CSV的Excel支持GBK编码，一定要转换，否则乱码
                        $head[$i] = iconv('utf-8', 'gbk', $v);
                }

                // 将数据通过fputcsv写到文件句柄
                fputcsv($fp, $head);
                // 计数器
                $cnt = 0;
                // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
                $limit = 100000;

                foreach($records as $row)
                {
                        $cnt ++;
                        if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                                ob_flush();
                                flush();
                                $cnt = 0;
                        }

                        foreach ($row as $i => $v) {
                                $row[$i] = iconv('utf-8', 'gbk', $v);
                        }
                        fputcsv($fp, $row);
                }
                $filename = "账单名单";
                // Redirect output to a client’s web browser (Excel2007)
                //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                //header('Content-Disposition: attachment;filename="01simple.xlsx"');
                //header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                //header('Cache-Control: max-age=1');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='.$filename.'.csv');
                header('Cache-Control: max-age=0');
                // If you're serving to IE over SSL, then the following may be needed
                //header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                //header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                //header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                //header ('Pragma: public'); // HTTP/1.0

                //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                //$objWriter->save('php://output');
                exit;

        }
        public static function server_checkOperator()
        {
                $account = $_POST['account'];
                $res = MysqlInterface::checkIfOperatorExist($account);
                if ($res)
                    echo "false";
                else
                    echo "true";
        }

        public static function server_checkEnterprise()
        {
                $account = $_POST['account'];
                $res = MysqlInterface::checkIfEnterpriseExist($account);
                if ($res)
                    echo "false";
                else
                    echo "true";
        }

}

