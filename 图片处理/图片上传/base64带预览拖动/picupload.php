<?php
header('Access-Control-Allow-Origin:*');
header("Content-type: application/json; charset=utf-8");
if(isset($_POST['images'])){
    $count = 0;
	$allowType = ['jpg','png','gif'];
	$paths = [];
    foreach($_POST['images'] as $image){
        if(strtolower($image['type']) == 'src'){    //当上传图片本身就是路径时（不需要bsae64转码）
            continue;//服务器不做处理，仅仅调整顺序
        }
		if(!in_array(strtolower($image['type']), $allowType)) continue;//过滤文件类型后缀
        $base_img = $image['file'];
        if (strstr($base_img, ",")){    //判断是否有逗号 如果有就截取后半部分
            $tempimg = explode(',', $base_img);
            $base_img = $tempimg[1];
        }
        //  设置文件路径和文件前缀名称
        $path = "./upload/";
        $prefix='nx_';
        $output_file = $prefix.time().rand(100,999).'.'.$image['type'];
        $path = $path.$output_file;
        //  创建将数据流文件写入我们创建的文件内容中
        $r = file_put_contents($path, base64_decode($base_img));
        $paths[] = $path ;
        $count++;
		//保存$path至数据库 
    }
    if($count){
        echo json_encode(["code"=>1,"msg"=>$count.'张图片上传完成']);
    }else{
        echo json_encode(["code"=>1,"msg"=>'没有图片被上传']);
    }
}else{
    echo json_encode(["code"=>0,"msg"=>'没有图片被上传']);
}