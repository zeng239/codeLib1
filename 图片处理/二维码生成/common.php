<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
include_once(EXTEND_PATH.'phpqrcode/phpqrcode.php'); 


//创建带背景的二维码并保存
/**
 * @baseUrl 连接
 * @addContent 附加参数，如会员id
 * @background  背景图
 * @x 生成的二维码在背景图上的横坐标
 * @y 生成的二维码在背景图上的纵坐标
 */
function createQrcodeAndSave($baseUrl, $addContent, $background, $x=0, $y=0)
{
    $saveDir = 'qrcode/';
    $content = $baseUrl. $addContent;
    $errorCorrectionLevel = 'L'; //容错级别
    $matrixPointSize = 6; //生成图片大小
    // 生成二维码图片
    QRcode::png($content, 'tempqrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);

    //生成中间带logo的二维码
    $BG = $background; // logo图片是你自己放到文件夹里的
    $QR = 'tempqrcode.png'; //已经生成的原始二维码图
    if(file_exists($BG))
    {
        $qr = imagecreatefromstring(file_get_contents($QR));
        $bg = imagecreatefromstring(file_get_contents($BG));

        $qr_width = imagesx($qr);
        $qr_height = imagesy($qr);
        $bg_width = imagesx($bg);
        $bg_height = imagesy($bg);

        //重新组合图片并调整大小
        $flag = imagecopyresampled($bg, $qr, $x, $y, 0, 0, $qr_width, $qr_height, $qr_width, $qr_height);
        imagepng($bg, $saveDir.'qrcode'.$addContent.'.png');
        imagedestroy($qr);  //释放内存
        imagedestroy($bg);
        return  $saveDir.'qrcode'.$addContent.'.png';
    }
    return false;

   
}