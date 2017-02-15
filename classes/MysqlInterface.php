<?php
require_once dirname(__FILE__).'/PermissionManager.php';
require_once dirname(__FILE__).'/Mysqli.php';
require_once(MUMPHPI_MAINDIR.'/classes/TranslationManager.php');
require_once(MUMPHPI_MAINDIR.'/classes/HelperFunctions.php');
require_once(MUMPHPI_MAINDIR.'/classes/MessageManager.php');

class MysqlInterface{

	public static function getOperators(){
		$sql = "select * from operator";
		$res = MysqlManager::getInstance()->getAll($sql);
		var_dump($res);
		return $res;

	}

	public static function getOperatorById($id){
		$sql = "select * from operator where id = ".$id;
        $res = MysqlManager::getInstance()->getRow($sql);
        var_dump($res);
        return $res;
	}

	public static function addOperator($parentId,$type, $name, $passwd, $email, $phone, $privilege){
        $data = array();
        $data['parentId']=$parentId; 
        $data['type'] = $type;
        $data['name']=$name; 
        $data['passwd']=md5($passwd); 
        $data['email']=$email; 
        $data['phone']=$phone; 
        $data['privilege']=$privilege; 
        $data['createTime']=date('Y-m-d H:i:s',time());
        $data['updateTime']=date('Y-m-d H:i:s',time());

        $res = MysqlManager::getInstance()->insert("operator", $data);
        
        return $res;
	}

	public static function deleteOperatorById($id){
		$where = "id = ".$id;
		$res = MysqlManager::getInstance()->deleteOne("operator", $where);
		return $res;
	}

	public static function updateOperator($id, $parentId, $name, $passwd, $email, $phone, $privilege){
        $data = array();
        $data['parentId']=$parentId; 
        $data['name']=$name; 
        $data['passwd']=md5($passwd); 
        $data['email']=$email; 
        $data['phone']=$phone; 
        $data['privilege']=$privilege; 
        $data['updateTime']=date('Y-m-d H:i:s',time());

        $res = MysqlManager::getInstance()->update("operator", $data, "id = $id");
	}

	public static function addOperationLog($operatorId, $operation){
		$data = array();
		$data['operatorId'] = $operatorId;
		$data['operation'] = $operation;
        $data['timestamp'] = date('Y-m-d H:i:s',time());
		$res = MysqlManager::getInstance()->insert("operationLog", $data);

	}

	public static function getOperationLogByOperatorId($operatorId){
		$sql = "select * from operationLog where operatorId = ".$operatorId;
        $res = MysqlManager::getInstance()->getAll($sql);
        var_dump($res);
        return $res;

	}
}


?>