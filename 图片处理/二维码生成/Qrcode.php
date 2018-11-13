<?php
namespace app\index\controller;

class Qrcode
{
    //创建带背景的二维码并保存
    public function create_save()
    {
        $qrcodeimg = createQrcodeAndSave("http://www.baidu.com/?",123456,'1080.png', 100, 100);
        echo '<img src="'.APP_ROOT_URL.$qrcodeimg.'">';
    }
    
}
