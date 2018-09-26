//I('tableStr') = 刘欢欢,410122198907158102,41011314001462#郑朝阳,410182198512146031,41011313005868#

//导出为Excel
public function tableToExcel(){
	$tableStr = htmlspecialchars_decode(I('tableStr'));
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename=data.xls");
	echo($tableStr);
}


//导出为Txt
public function tableToTxt(){
	$txtStr = htmlspecialchars_decode(I('txtStr'));
	header( "Content-type:   application/octet-stream "); 
	header( "Accept-Ranges:   bytes "); 
	header( "Content-Disposition:   attachment;   filename=test.txt "); 
	header( "Expires:   0 "); 
	header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
	header( "Pragma:   public ");
	$txtStr = str_replace('#', "\r\n", $txtStr);
	echo($txtStr);
}
