<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return 'TP5支付<p><a href="'.APP_ROOT_URL.'index.php/index/Pay/alipay1">支付宝移移动版</a></p>'
            .'<p><a href="http://demo1.weiyunstudio.com/PayInTP5/public/alipay.trade.wap.pay-PHP-UTF-8/wappay/pay.php">支付宝移动版原版支付</a></p>';
    }

    
}
