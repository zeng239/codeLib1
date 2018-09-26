<?php
namespace Home\Controller;
use Think\Controller;
class PicController extends Controller {
    public function index(){
        $this->display();
    }
    
    //处理图片上传
    public function doUpload(){
    	file_put_contents("piclog.txt","Hello World. Testing!\r\n",FILE_APPEND);
    	$upload = new \Think\Upload();// 实例化上传类    
    	$upload->rootPath = './Public/';	//默认是Uploads,需要手工创建
    	$upload->maxSize   =     3145728 ;// 设置附件上传大小    
    	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
    	$upload->savePath  =    'Uploads/'; // 设置附件上传目录    
    	$info   =   $upload->upload();        	// 上传文件 
    	
    	if(!$info) {// 上传错误提示错误信息   
    		//file_put_contents("piclog.txt",$upload->getError()."\r\n",FILE_APPEND);
    		$this->error($upload->getError());    
    	}else{// 上传成功        
    		foreach($info as $file){        
    			$res =  $file['savepath'].$file['savename']; 
    		//	file_put_contents("piclog.txt",$res."\r\n",FILE_APPEND);
    		}
    		$this->success('上传成功！');
    	}
    }
}