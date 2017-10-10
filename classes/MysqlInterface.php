<?php
require_once dirname(__FILE__).'/PermissionManager.php';
require_once dirname(__FILE__).'/Mysqli.php';
require_once(MUMPHPI_MAINDIR.'/classes/TranslationManager.php');
require_once(MUMPHPI_MAINDIR.'/classes/HelperFunctions.php');
require_once(MUMPHPI_MAINDIR.'/classes/MessageManager.php');

class MysqlInterface{

/*	public static function getOperatorsByOperatorId(){
		$sql = "select * from operator where type = 2";
		$res = MysqlManager::getInstance()->getAll($sql);
		return $res;

	}*/
    public static function getOperatorsByAdmin(){
        $sql = "select * from operator where type = 1";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;

    }
    public static function checkOperatorLogin($account , $passwd){
        $account = MysqlManager::getInstance()->escape_string($account);
        $sql = "select * from operator where account = '".$account."' and passwd = '".md5($passwd)."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        if (count($res)>0) return true;
        return false;
    }
    public static function getOperatorById($id){
        $sql = "select * from operator where id = ".$id ;
        $res = MysqlManager::getInstance()->getRow($sql);
        return $res;
    }
    public static function checkIfOperatorCanDispatcher($id, $cardNum, $pCardNum){
        $sql = "select * from operator where id = ".$id ;
        $res = MysqlManager::getInstance()->getRow($sql);
        if ($res['availableCards'] >= $cardNum && $res['availablePCards'] >= $pCardNum)
            return true;
        return false;
    }
    public static function checkIfEnterpriseCanDispatcher($id, $cardNum){
        $sql = "select * from enterprise where id = ".$id ;
        $res = MysqlManager::getInstance()->getRow($sql);
        if ($res['availableCards'] >= $cardNum)
            return true;
        return false;
    }
    public static function checkIfEnterpriseCanCreateUser($id, $type){
        $sql = "select * from enterprise where id = ".$id ;
        $res = MysqlManager::getInstance()->getRow($sql);
        if($type == 0)
        {
            if($res['availablePCards'] > 0)
                return true;
        }else if($type == 1)
        {
            if($res['availableCards'] > 0)
                return true;
        }
        return false;
    }
    public static function getOperatorByName($name){
        $sql = "select * from operator where account = '".$name."'";
        $res = MysqlManager::getInstance()->getRow($sql);
        return $res;
    }
    public static function getOperatorsByParentId($id){
        $sql = "select * from operator where parentId = ".$id." and type = 2";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;

    }

    public static function searchOperatorsByAdmin($type, $value){ //type = 1 by account type = 2 by name type = 3 by pemail type = 4 by phone
        $sql = '';
        if ($type == 1)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where account like '%".$value."%' and type = 1";
        }else if ($type == 2)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where name like '%".$value."%' and type = 1";
        }else if ($type == 3)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where email like '%".$value."%' and type = 1";
        }else if ($type == 4)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where phone like '%".$value."%' and type = 1";
        }else
        {
            return "unsurport search type";
        }

        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchOperatorsByParentId($type, $value, $parentId){ //type = 1 by account type=2 by name type = 3 by email type = 4 by phone
        $sql = '';
        if ($type == 1)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where account like '%".$value."%' and parentId=".$parentId;
        }else if ($type ==2 )
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where name like '%".$value."%' and parentId=".$parentId;
        }
        else if ($type == 3)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where email like '%".$value."%' and parentId=".$parentId;
        }else if ($type == 4)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from operator where phone like '%".$value."%' and parentId=".$parentId;
        }else
        {
            return "unsurport search type";
        }

        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }

    public static function addOperator($parentId,$type, $account, $passwd,$name,$comment, $email, $phone, $privilege){
        $data = array();
        $data['parentId']=$parentId; 
        $data['type'] = $type;
        $data['account']=$account; 
        $data['passwd']=md5($passwd); 
        $data['name']=$name; 
        $data['comment']=$comment; 
        $data['email']=$email; 
        $data['phone']=$phone; 
        $data['privilege']=$privilege; 
        $data['createTime']=date('Y-m-d H:i:s',time());
        $data['updateTime']=date('Y-m-d H:i:s',time());

        $data['availableCards']=0; 
        $data['availablePCards']=0; 
        $data['availableGroups']=0; 
        $res = MysqlManager::getInstance()->insert("operator", $data);

        return $res;
    }

    public static function deleteOperatorById($id){
        $where = "id = ".$id;
        $res = MysqlManager::getInstance()->deleteOne("operator", $where);
        return $res;
    }

    public static function updateOperator($id, $parentId,$passwd, $name, $comment, $email, $phone, $privilege){
        $data = array();
        if (!is_null($parentId))
        {
            $data['parentId']=$parentId; 
        }
        if (!is_null($passwd))
        {
            $data['passwd']=md5($passwd); 
        }

        $data['name']=$name; 
        $data['comment']=$comment; 
        $data['email']=$email; 
        $data['phone']=$phone; 
        if (!is_null($privilege))
        {
            $data['privilege']=$privilege; 
        }
        $data['updateTime']=date('Y-m-d H:i:s',time());

        $res = MysqlManager::getInstance()->update("operator", $data, "id = $id");
        return $res;
    } 

    public static function checkIfOperatorExist($account)
    {
        $account = MysqlManager::getInstance()->escape_string($account);
        $sql = "select * from operator where account = '".$account."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        if (count($res)>0) return true;
        return false;
    }

    public static function addEnterprise($parentId,$type, $account, $passwd,$name, $email, $phone,$comment, $privilege){
        $data = array();
        $data['parentId']=$parentId;
        $data['type'] = $type;
        $data['account']=$account;
        $data['passwd']=md5($passwd);
        $data['name']=$name;
        $data['email']=$email;
        $data['phone']=$phone;
        $data['comment']=$comment;
        $data['privilege']=$privilege;
        $data['createTime']=date('Y-m-d H:i:s',time());
        $data['updateTime']=date('Y-m-d H:i:s',time());
        $data['availableCards']=0;
        $data['availablePCards']=0;

        $res = MysqlManager::getInstance()->insert("enterprise", $data);

        return $res;
    }

    public static function updateEnterprise($id, $parentId,$passwd, $name, $email, $phone,$comment, $privilege){
        $data = array();
        if (!is_null($parentId))
        {
            $data['parentId']=$parentId;
        }
        if (!is_null($passwd))
        {
            $data['passwd']=md5($passwd);
        }

        $data['name']=$name;
        $data['email']=$email;
        $data['phone']=$phone;
        $data['comment']=$comment;
        if (!is_null($privilege))
        {
            $data['privilege']=$privilege;
        }

        $data['updateTime']=date('Y-m-d H:i:s',time());

        $res = MysqlManager::getInstance()->update("enterprise", $data, "id = $id");
        return $res;
    }

    public static function checkIfEnterpriseExist($account)
    {
        $account = MysqlManager::getInstance()->escape_string($account);
        $sql = "select * from enterprise where account = '".$account."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        if (count($res)>0) return true;
        return false;
    }
    public static function deleteEnterpriseById($id){
        $id = intval($id);
        $where = "id = ".$id;
        $res = MysqlManager::getInstance()->deleteOne("enterprise", $where);
        return $res;
    }
    public static function searchEnterprisesByParentId($type, $value, $parentId){ //type = 1 by account type=2 by name type = 3 by email type = 4 by phone
        $sql = '';
        if ($type == 1)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from enterprise where account like '%".$value."%' and parentId=".$parentId;
        }else if ($type ==2 )
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from enterprise where name like '%".$value."%' and parentId=".$parentId;
        }
        else if ($type == 3)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from enterprise where email like '%".$value."%' and parentId=".$parentId;
        }else if ($type == 4)
        {
            $sql = "select id,account,name,comment,email,phone,availableCards, availablePCards from enterprise where phone like '%".$value."%' and parentId=".$parentId;
        }else
        {
            return "unsurport search type";
        }

        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchEnterprises($type, $value){ //type = 1 by name type = 2 by email type = 3 by phone
        $sql = '';
        if ($type == 1)
        {
            $sql = "select * from enterprise where name like '%".$value."%'  ";
        }else if ($type == 2)
        {
            $sql = "select * from enterprise where email like '%".$value."%' ";
        }else if ($type == 3)
        {
            $sql = "select * from enterprise where phone like '%".$value."%' ";
        }else
        {
            return "unsurport search type";
        }

        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getEnterpriseById($id){
        $sql = "select * from enterprise where id = ".$id ;
        $res = MysqlManager::getInstance()->getRow($sql);
        return $res;
    }
    public static function getEnterpriseByName($name){
        $sql = "select * from enterprise where account = '".$name."'";
        $res = MysqlManager::getInstance()->getRow($sql);
        return $res;
    }
    public static function checkEnterpriseLogin($username , $passwd){
        $sql = "select * from enterprise where account='".$username."' and passwd ='".md5($passwd)."'";

        $res = MysqlManager::getInstance()->getAll($sql);
        if (count($res)>0) return true;
        return false;
    }
    public static function checkEnterpriseLoginById($id , $passwd){
        $sql = "select * from enterprise where id=".$id." and passwd ='".md5($passwd)."'";

        $res = MysqlManager::getInstance()->getAll($sql);
        if (count($res)>0) return true;
        return false;
    }

    public static function getEnterprises(){
        $sql = "select * from enterprise";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;

    }
    public static function getEnterprisesByParentId($id){
        $sql = "select * from enterprise where parentId=".$id;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;

    }

    public static function changeEnterprisesParentId($id){
        $sql = "select * from operator where id=".$id;
        $res1 = MysqlManager::getInstance()->getAll($sql);
        if(count($res1) == 0) return;
        foreach($res1 AS $item)
        {
            $newid = $item['parentId'];
        }

        $data = array();
        $data['parentId']=$newid;
        $res = MysqlManager::getInstance()->update("enterprise", $data, "parentId = $id");
        return $res;
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
        return $res;

    }



















    public static function addRecord($fromId,$fromType,$toId,$toType,$cardNum,$pCardNum, $cost){
        $data = array();
        $data['fromId']=$fromId;
        $data['fromType']=$fromType;
        $data['toId']=$toId;
        $data['toType']=$toType;
        $data['cardNum']=$cardNum;
        $data['pCardNum']=$pCardNum;
        $data['groupNum']=0;
        $data['cost'] = $cost;
        $data['createTime']=date('Y-m-d H:i:s',time());
        $res = MysqlManager::getInstance()->insert("record", $data);
    }
    public static function updateRecordByAdmin($id, $operatorId, $allCards){
        $data = array();
        $data['operatorId']=$operatorId;
        $data['allCards'] = $allCards;
        $res = MysqlManager::getInstance()->update("record", $data, "id = $id");
    }

    public static function getRecordsByAdmin(){
        $sql = "select r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId =o.id ";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordsByOperator1FromAdmin($operatorId){
        $sql = "select distinct r.id,r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toId=".$operatorId;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordsByOperator2FromOperator1($operatorId){
        $sql = "select distinct r.id,r.fromId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and o.id = r.fromId and r.toId=".$operatorId;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordsByOperator1ToOperator2($operatorId){
        $sql = "select distinct r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and o.id = r.toId and r.fromId=".$operatorId;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordsByOperatorToEnterprise($operatorId){
        $sql = "select distinct r.id,r.toId, e.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, enterprise e  where r.fromType = 2 and r.toType = 3 and e.id = r.toId and r.fromId=".$operatorId;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordsByEnterprise($enterpriseId){
        $sql = "select distinct r.id,r.fromId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 3 and o.id = r.fromId and r.toId=".$enterpriseId;
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function getRecordById($id){
        $sql = "select * from record where id=".$id;
        $res = MysqlManager::getInstance()->getRow($sql);
        return $res;
    }


    public static function searchRecordsByAdmin($type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId =o.id";
            if ($value != "")
                $sql = $sql." and r.toId=".$value;


        }else if ($type == 2)
        {
            $sql = "select r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId =o.id";
            if ($value != "")
                $sql = $sql." and o.name like '%".$value."%'";

        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchRecordsOperatorFromAdmin($operatorId, $startTime, $endTime){
        $sql = "select distinct r.id, r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId=".$operatorId;

        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        var_dump($sql);
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }


    public static function searchRecordsOperator1ToOperator2($operatorId, $type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select distinct r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and r.fromId=".$operatorId." and r.toId =o.id";
            if ($value != "")
                $sql = $sql." and r.toId=".$value;

        }else if ($type == 2)
        {
            $sql = "select distinct r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and r.fromId=".$operatorId." and r.toId =o.id";
            if ($value != "")
                $sql = $sql." and o.name like '%".$value."%'";

        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchRecordsOperator2FromOperator1($operatorId, $type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select distinct r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and r.toId=".$operatorId;
            if ($value != "")
                $sql = $sql." and r.fromId=".$value;

        }else if ($type == 2)
        {
            $sql = "select distinct r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 2 and r.toType = 2 and r.toId=".$operatorId;
            if ($value != "")
                $sql = $sql." and o.name like '%".$value."%'";

        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchRecordsOperatorToEnterprise($operatorId, $type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select distinct r.id,r.toId, e.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o, enterprise e where r.fromType = 2 and r.toType = 3 and r.toId=e.id and r.fromId=".$operatorId;

            if ($value != "")
                $sql = $sql." and r.toId=".$value." and e.id=".$value;
        }else if ($type == 2)
        {
            //$sql = "select distinct r.id,r.toId, e.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, enterprise e where r.fromType = 2 and r.toType = 3 and r.fromId =".$operatorId;
            $sql = "select distinct record.id,record.toId, enterprise.name , record.cardNum, record.pCardNum, record.createTime, record.cost from record  join  enterprise on record.fromType = 2 and record.toType = 3 and record.toId=enterprise.id and record.fromId =".$operatorId;
            if ($value != "")
                $sql = $sql." and enterprise.name like '%".$value."%'";
        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function searchRecordsEnterpriseFromOperator($enterpriseId, $type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select distinct r.id,r.fromId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o, enterprise e where r.fromType = 2 and r.toType = 3 and r.toId= ".$enterpriseId." and r.fromId=".$value;

        }else if ($type == 2)
        {
            $sql = "select distinct r.id,r.fromId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o, enterprise e where r.fromType = 2 and r.toType = 3 and r.toId = ".$enterpriseId." and o.name like '%".$value."%'";
        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }

    public static function searchRecordsByEnterprise($type, $value, $startTime, $endTime){
        $sql = '';
        if ($type == 1)
        {
            $sql = "select r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId =o.id and r.toId=".$value;

        }else if ($type == 2)
        {
            $sql = "select r.id,r.toId, o.name , r.cardNum, r.pCardNum, r.createTime, r.cost from record r, operator o  where r.fromType = 1 and r.toType = 2 and r.toId =o.id and o.name like '%".$value."%'";
        }else 
        {
            return "";
        }	
        if ($startTime != "")
            $sql = $sql." and r.createTime >='".$startTime."'";
        if ($endTime != "")
            $sql = $sql." and r.createTime <='".$endTime."'";
        $res = MysqlManager::getInstance()->getAll($sql);
        return $res;
    }
    public static function deleteRecordById($id){
        $where = "id = ".$id;
        $res = MysqlManager::getInstance()->deleteOne("record", $where);
        return $res;
    }

    public static function addAdminToOperatorDispatcher($operatorId, $newCards, $pCards)
    {
        $data = array();
        $data['id']=$operatorId;
        $data['availableCards'] = $newCards;
        $data['availablePCards'] = $pCards;
        //$data['availableGroups'] = $newGroups;
        $res = MysqlManager::getInstance()->update("operator", $data, "id = $operatorId");
        return $res;
    }

    public static function addOperatorToOperatorDispatcher($parentId, $deleteNum,$deletePNum, $operatorId, $newCards,$newPCards)
    {
        //要应用mysql的事务机制，要用户的年卡数和代理商的年卡数同时生效才算操作成功，设置mysql不能自动commit
        MysqlManager::getInstance()->setAutoCommit(false);
        //$data1 = array();
        //$data1['id']=$enterpriseId;
        //$data1['availableCards'] = $newCards;
        //$data['availableGroups'] = $newGroups;
        $sql1 = "update operator set availableCards = ".$newCards.", availablePCards=".$newPCards." where id = ".$operatorId;
        $res1 = MysqlManager::getInstance()->queryNotAutoCommit($sql1);
        //减掉代理相应的年卡数
        $sql2 = "update operator set availableCards = availableCards-".$deleteNum.", availablePCards = availablePCards-".$deletePNum." where id=".$parentId;
        //$data['availableGroups'] = $newGroups;
        //$res2 = MysqlManager::getInstance()->update("operator", $data, "id = $operatorId");
        $res2 = MysqlManager::getInstance()->queryNotAutoCommit($sql2);
        if ($res1 && $res2)
        {
            MysqlManager::getInstance()->commit();  
        }else
        {
            MysqlManager::getInstance()->rollback();
        }
        //MysqlManager::getInstance()->setAutoCommit(true);

        return $res1&&$res2;
    }



    public static function addOperatorToEnterpriseDispatcher($parentId, $deleteNum,$deletePNum, $enterpriseId, $newCards,$newPCards)
    {
        //要应用mysql的事务机制，要用户的年卡数和代理商的年卡数同时生效才算操作成功，设置mysql不能自动commit
        MysqlManager::getInstance()->setAutoCommit(false);
        //$data1 = array();
        //$data1['id']=$enterpriseId;
        //$data1['availableCards'] = $newCards;
        //$data['availableGroups'] = $newGroups;
        $sql1 = "update enterprise set availableCards = ".$newCards.", availablePCards = ".$newPCards." where id = ".$enterpriseId;
        //$res1 = MysqlManager::getInstance()->update("enterprise", $data1, "id = $enterpriseId");
        $res1 = MysqlManager::getInstance()->queryNotAutoCommit($sql1);
        //减掉代理相应的年卡数
        $sql2 = "update operator set availableCards = availableCards-".$deleteNum.", availablePCards = availablePCards-".$deletePNum." where id=".$parentId;
        //$data['availableGroups'] = $newGroups;
        //$res2 = MysqlManager::getInstance()->update("operator", $data, "id = $operatorId");
        $res2 = MysqlManager::getInstance()->queryNotAutoCommit($sql2);
        if ($res1 && $res2)
        {
            MysqlManager::getInstance()->commit();  
        }else
        {
            MysqlManager::getInstance()->rollback();
        }
        //MysqlManager::getInstance()->setAutoCommit(true);

        return $res1&&$res2;
    }

    public static function chargeEnterpriseCardNum($entId, $type)
    {
        $sql = "";
        $res;
        MysqlManager::getInstance()->setAutoCommit(false);
        if($type == 0)
        {
            $sql = "update enterprise set availablePCards = availablePCards-1 where id=".$entId;
            $res = MysqlManager::getInstance()->queryNotAutoCommit($sql);
        }else if ($type == 1)
        {
            $sql = "update enterprise set availableCards = availableCards-1 where id=".$entId;
            $res = MysqlManager::getInstance()->queryNotAutoCommit($sql);
        }
        MysqlManager::getInstance()->commit();
        return $res; 

    }
}


?>
