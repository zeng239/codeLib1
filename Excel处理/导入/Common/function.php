<?php
	include_once('./PHPExcel/PHPExcel.php');
	
	function fishTest(){
		echo("this is a function.");
	}
	
	//从身份证提取生日
	function getBirthdayFromIdnum($idnum){
		$str = strlen($idnum)==15 ? ('19' . substr($idnum, 6, 6)) : substr($idnum, 6, 6);
		return $str;
	}
	
	function excelRead($filename,$encode='utf-8'){
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		$highestColumn = $objWorksheet->getHighestColumn(); 
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		$excelData = array(); 
		for ($row = 1; $row <= $highestRow; $row++) { 
			for ($col = 0; $col < $highestColumnIndex; $col++) { 
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
           	} 
        } 
        return $excelData;
    }
	
	function addlog($name, $value, $type){
		$logModel = M('Log');
		$data['name'] = $name;
		$data['value'] = $value;
		$data['type'] = $type;
		$data['time'] = date("y-m-d h:i:s",time());
		$logModel->add($data);
	}
