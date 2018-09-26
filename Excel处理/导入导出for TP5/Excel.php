<?php
namespace app\excel\controller;
use \think\Controller;
use \think\Loader;
use \think\Request;
use \think\Db;

//PHPExcel放到extend目录下

class Excel extends Controller
{
    public function index()
    {
        return 'index';
    }

    //导出测试
    public function exportTest(){
        $title = ['测试1','222','333'];
        $data = [[111,222,333],['aaa','bbb','vvv'],['aaa','bbb','vvv'],['aaa','bbb','vvv'],['aaa','bbb','vvv']];
        $saveFileName = $this->exportExcel($title,  $data, '', './', true);
        return  $saveFileName;
    }

    //批量导入数据，只能接受xls类型Excel
    //第一行是字段名
    //第二行是中文说明，从第三行起是数据
    public function includeFile(){
        if(Request::instance()->isPost()){
            $file = request()->file('excelFile');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $uploadPath = ROOT_PATH . 'public' . DS . 'uploads'. DS .'excel'. DS;
                $info = $file->move($uploadPath);
                if($info){
                // 成功上传后 获取上传信息
                $saveName = $info->getSaveName();
                $excelArray =   $this->excelRead( $uploadPath.$saveName);
                $columnNameList = $excelArray[1];
                $valueList = array_slice($excelArray, 2);
                $data = array();
                for($i = 0; $i < count($valueList); $i++){
                    for($j = 0;$j < count($columnNameList); $j++){
                        $data[$i][$columnNameList[$j]] = $valueList[$i][$j];
                    }
                }

                $dbname = input('post.dbname');
                $this->insertAllToDatabase($dbname, $data);
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
                return '导入成功';
            }
                return '没有提交文件';
        }else{
            return $this->fetch();
        }
       
    }

     //单条记录插入数据表
     public function insertToDatabase($table, $data){
        return Db::name($table)->insert($data);
    }

    //所有记录插入数据表
    public function insertAllToDatabase($table, $data){
        Db::name($table)->insertAll($data);
    }

    //读取excel
    public function excelRead($filename, $encode='utf-8'){
        include_once(ROOT_PATH.'/extend/PHPExcel/PHPExcel.php');

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		$highestColumn = $objWorksheet->getHighestColumn(); 
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array(); 
		for ($row = 1; $row <= $highestRow; $row++) { 
			for ($col = 0; $col < $highestColumnIndex; $col++) { 
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
           	} 
        } 
        return $excelData;
    }

    //导出excel
    /** 
     * 数据导出 
     * @param array $title   标题行名称 
     * @param array $data   导出数据 
     * @param string $fileName 文件名 
     * @param string $savePath 保存路径 
     * @param $type   是否下载  false--保存   true--下载 
     * @return string   返回文件全路径 
     * @throws PHPExcel_Exception 
     * @throws PHPExcel_Reader_Exception 
     */  
    function exportExcel($title=array(), $data=array(), $fileName='', $savePath='./', $isDown=false){  
        include_once(ROOT_PATH.'/extend/PHPExcel/PHPExcel.php');
        $obj = new \PHPExcel();  
    
        //横向单元格标识  
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
        
        $obj->getActiveSheet(0)->setTitle('sheet');   //设置sheet名称  
        $_row = 1;   //设置纵向单元格标识  
        if($title){  
            $_cnt = count($title);  
            $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$_cnt-1].$_row);   //合并单元格  
            $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容  
            $_row++;  
            $i = 0;  
            foreach($title AS $v){   //设置列标题  
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);  
                $i++;  
            }  
            $_row++;  
        }  
    
        //填写数据  
        if($data){  
            $i = 0;  
            foreach($data AS $_v){  
                $j = 0;  
                foreach($_v AS $_cell){  
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);  
                    $j++;  
                }  
                $i++;  
            }  
        }  
        
        //文件名处理  
        if(!$fileName){  
            $fileName = uniqid(time(),true);  
        }  
    
        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');  
    
        if($isDown){   //网页下载  
            header('pragma:public');  
            header("Content-Disposition:attachment;filename=$fileName.xls");  
            $objWrite->save('php://output');exit;  
        }  
    
        $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码  
        $_savePath = $savePath.$_fileName.'.xls';  
        $objWrite->save($_savePath);  
    
        return $savePath.$fileName.'.xls';
    }  

    //清除临时文件
    public function clearExcel(){
        $uploadPath = ROOT_PATH . 'public' . DS . 'uploads'. DS .'excel'. DS;
        $this->del_dir($uploadPath);
    }

    //遍历删除目录和目录下所有文件
    private function del_dir($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file != "." && $file != "..") {
                is_dir("$dir/$file") ? del_dir("$dir/$file") : @unlink("$dir/$file");
            }
        }
        if (readdir($handle) == false) {
            closedir($handle);
            @rmdir($dir);
        }
    }

}
