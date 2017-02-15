<?php
include(dirname(__FILE__).'/Mysql.php');
require_once(MUMPHPI_MAINDIR . '/classes/SettingsManager.php');
require_once(MUMPHPI_MAINDIR . '/classes/MessageManager.php');
require_once(MUMPHPI_MAINDIR . '/classes/TranslationManager.php');

class MysqlManager{
    //数据表名
    protected $tablename = '';
    //设置数组
    protected $options = array();
    //表域结构
    protected $info = array();
    //表的例名称
    protected $colsName = array();
    //Db类操作实例
    protected $db = null;
    //测试debug
    protected $debug = false;
    //要插入的数据
    protected $data = array();
    
    public function __construct($tablename){
        $this->debug = true;
    	$this->tablename = $tablename;
        $this->options['table'] = $tablename;
        
        $this->init();
         
    }
    //初始化db数据库句柄
    //获取选定表的域
    public function init(){
        //获取Db类的实例
        if(is_null($this->db)){
        	$this->db = Db::getInstance(SettingsManager::getInstance()->getMysqlConfig());
        }
        $this->info = $this->db->getFields($this->tablename);
        foreach($this->info as $keys=>$values){
            //自增域名除外
            if(!$values['autoinc'])
        		$this->colsName[] = $keys;
            if($this->debug){
				echo $keys.":<br>";
                foreach($values as $key=>$value){
                    echo $key." : ".$value."   ";
                }
                echo "<br>";
				
            }
        }
        if($this->debug){
            echo __LINE__." :";
			print_r($this->colsName);
            echo "<p>";
        }
    }
    public function free(){
		$this->data=array();
	}
    //建造符合当前数据表插入
    //data为要插入的数据
    public function createInsert($data){
		$this->free();
        if(empty($data))
        {
            return false;
        }
        $myData=array();
        foreach($data as $value){
        	$myData[] = $value;
        }
        for($i = 0;$i < count($this->colsName);$i++){
            //说明不能为null出为空
            if($this->info[$this->colsName[$i]][notnull] && (is_null($myData[$i])||$myData[$i]==''))
            { 
                return false; 	
            }
           	$this->data[$this->colsName[$i]] = $myData[$i];
        }
		return true;
    }
	//建造符合当前数据表插入
    //data为要更改的数据
    public function createUpdate($data){
		$this->free();
		if(!empty($data)){
			foreach($data as $key=>$value){
				if(empty($this->info[$key])){
					return false;
				}else{
					$this->data=$data;
				}
			}
		}
		return true;
	}
    //设置条件函数
    public function setOptionsWhere($where){
		$this->options['where']='';
        //是字符串
        if(is_string($where)){
            $this->options['where'] = $where;
        }
        //是数组表示的多条件
        else{
        	$tmpWhere='';
            foreach($where as $value){
                if(!is_string($value)){
                	return false;
                }
            	$tmpWhere.=$value;
            }
            $this->options['where'] = $tmpWhere;
        }
    }
	//设置条件函数
    public function setOptionsField($field){
		$this->options['field']='';
        //是字符串
        if(is_string($field)){
            $this->options['field'] = $field;
        }
        //是数组表示的多条件
        else{
        	$tmpField='';
            foreach($field as $value){
                if(!is_string($value)){
                	return false;
                }
            	$tmpField.=$value.",";
            }
            $this->options['field'] = substr($tmpField,0,-1);;
            
        }
    }
	
	//增删查改
	public function insert($data=''){
		if($data!=''){
			if($this->debug){
				echo '<br>进入createInsert()<p>';
			}
			if(!$this->createInsert($data)){
				if($this->debug){
					echo 'createInsert()失败<p>';
					print_r($this->data);
				}
				return false;
			}
		}else{
			if(empty($this->data)){
				return false;
			}
		}
		return $this->db->insert($this->data,$this->options);
	}
	public function _insert($data,$options){
		return $this->db->insert($data,$options);
	}
	
	public function update($data){
		if($data!=''){
			if(!$this->createUpdate($data)){
				return false;
			}
		}else{
			if(empty($this->data)){
				return false;
			}
		}
		return $this->db->update($this->data,$this->options);
	}
	public function _update($data,$options){
		return $this->db->update($data,$options);
	}
	public function delete(){
		return $this->db->delete($this->options);
	}
	public function select(){
		return $this->db->select($this->options);
	}
	public function _delete($options){
		return $this->db->delete($options);
	}
	public function _select($options){
		return $this->db->select($options);
	}
	
	//获取参数函数
    public function getData(){
    	return $this->data;
    }
    public function getOptions(){
    	return $this->options;
    }
    public function getTablename(){
    	return $this->tablename;
    }
    public function getInfo(){
    	return $this->info;
    }
    public function getColsname(){
    	return $this->colsName;
    }
    
}
?>
