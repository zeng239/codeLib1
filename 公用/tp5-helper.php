<?php
///////////////Thinkphp5.x 无痛兼容助手 by zhuanqianfish/////////////
//// 在扩展函数文件中加入即可像TP3中那样使用 C() D() I() M() U() 等方法
//// 'extra_file_list'        => [THINK_PATH . 'helper' . EXT,  APP_PATH . 'helper-tp5.php'],
use \think\Model;
use \think\Request;

function helpertest(){
	return 'helpertest';
}

//C方法
if (!function_exists('C')) {
	/**
	 * 采有TP5最新助手函数特性实现函数简写方式 S 
	 * 获取和设置配置参数 支持批量定义
	 * @param string|array $name 配置变量
	 * @param mixed $value 配置值
	 * @param mixed $default 默认值
	 * @return mixed
	 */
		function C($name=null, $value=null,$default=null) {
			return config($name);
	   }   
	}

//D方法
if (!function_exists('D')) {
    /**
     * 采有TP5最新助手函数特性实现函数简写方式 D
     * @param string $name 表名     
     * @return DB对象
     */
    function D($name = '')
    {               
        $name = Loader::parseName($name, 1); // 转换驼峰式命名
        if(is_file(APP_PATH."/".MODULE_NAME."/model/$name.php")){
            $class = '\app\\'.MODULE_NAME.'\model\\'.$name;
        }elseif(is_file(APP_PATH."/home/model/$name.php")){
            $class = '\app\home\model\\'.$name;
        }elseif(is_file(APP_PATH."/mobile/model/$name.php")){
            $class = '\app\mobile\model\\'.$name;
        }elseif(is_file(APP_PATH."/api/model/$name.php")){            
            $class = '\app\api\model\\'.$name;     
        }elseif(is_file(APP_PATH."/admin/model/$name.php")){
            $class = '\app\admin\model\\'.$name;
        }elseif(is_file(APP_PATH."/seller/model/$name.php")){
            $class = '\app\seller\model\\'.$name;
        }
        if($class)
        {
            return new $class();
        }            
        elseif(!empty($name))
        {          
            return Db::name($name);
        }                    
    }
}

//I方法
if (!function_exists('I')) {
    /**
     * 采有TP5最新助手函数特性实现函数简写方式 I
     * 获取输入参数 支持过滤和默认值
     * 使用方法:
     * <code>
     * I('id',0); 获取id参数 自动判断get或者post
     * I('post.name','','htmlspecialchars'); 获取$_POST['name']
     * I('get.'); 获取$_GET
     * </code>
     * @param string $name 变量的名称 支持指定类型
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $datas 要获取的额外数据源
     * @return mixed
     */
    function I($name,$default='',$filter='htmlspecialchars',$datas=null) {
     
        $value = input($name,'',$filter);        
        if($value !== null && $value !== ''){
            return $value;
        }
        if(strstr($name, '.'))  
        {
            $name = explode('.', $name);
            $value = input(end($name),'',$filter);           
            if($value !== null && $value !== '')
                return $value;            
        }  
        return $default;        
    } 
}
	

//M方法

if (!function_exists('M')) {
    /**
     * 采有TP5最新助手函数特性实现函数简写方式 M
     * @param string $name 表名     
     * @return DB对象
     */
    function M($name = '')
    {
        if(!empty($name))
        {          
            return Db::name($name);
        }                    
    }
}

if (!function_exists('F')) {
	/**
	 * 采有TP5最新助手函数特性实现函数简写方式 F
	* @param mixed $name 缓存名称，如果为数组表示进行缓存设置
	* @param mixed $value 缓存值
	* @param mixed $path 缓存参数
	* @return mixed
	*/
   function F($name,$value='',$path='') {
	   if(!empty($value))
			Cache::set($name,$value);
	   else
		   return Cache::get($name);
   }
}

if (!function_exists('U')) {
    /**
     * 采有TP5最新助手函数特性实现函数简写方式 M
     * URL组装 支持不同URL模式
     * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
     * @param string|array $vars 传入的参数，支持数组和字符串
     * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
     * @param boolean $domain 是否显示域名
     * @return string
     */
    function  U($url='',$vars='',$suffix=true,$domain=false) 
    {
       return Url::build($url, $vars, $suffix, $domain);
    }
}

if (!function_exists('S')) {
    /**
     * 采有TP5最新助手函数特性实现函数简写方式 S 
    * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
    * @param mixed $value 缓存值
    * @param mixed $options 缓存参数
    * @return mixed
    */
   function S($name,$value='',$options=null) {
       if(!empty($value))
            Cache::set($name,$value,$options);
       else
           return Cache::get($name);
   }
}

//是否为POST请求
if (!function_exists('IS_POST')) {
	function IS_POST(){
		return Request::instance()->isPost();
	}
}


//是否为GET请求
if (!function_exists('IS_GET')) {
	function IS_GET(){
		return (Request::instance()->isGet());
	}
}

//获取一条记录
function getOne($modelName, $condition=[]){
	return Db::name($modelName)->where($condition)->find();
}

//获取一条记录
function getOneValue($modelName, $field, $condition=[]){
	return Db::name($modelName)->where($condition)->value($field);
}

//获取某一列的值array
//$fields 如：'id, name'
function getColumn($modelName, $fields, $condition=[]){
	return Db::name($modelName)->where($condition)->column($fields);
}

//获取带分页记录列表
function getList($modelName, $condition=[], $page=0){
	if($page > 0){
		return Db::name($modelName)->where($condition)->paginate($page);
	}else{
		return Db::name($modelName)->where($condition)->select();
	}
}