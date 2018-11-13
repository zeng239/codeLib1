<?php
namespace app\index\controller;
use \think\Controller;

class Pay extends Controller
{
    public function index()
    {
        return '支付';
    }

    //支付宝移动版
    public function alipay1(){
        if (\think\Request::instance()->isPost()){
            header("Content-type: text/html; charset=utf-8");
            require_once ALIPAY_DEMO_PATH.'wappay/service/AlipayTradeService.php';
            require_once ALIPAY_DEMO_PATH.'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
            require ALIPAY_DEMO_PATH.'./config.php';
            if (!empty($_POST['WIDout_trade_no'])&& trim($_POST['WIDout_trade_no'])!=""){
                //商户订单号，商户网站订单系统中唯一订单号，必填
                $out_trade_no = $pay_type."#".$_POST['WIDout_trade_no'];

                //订单名称，必填
                $subject = $_POST['WIDsubject'];

                //付款金额，必填
                $total_amount = $_POST['WIDtotal_amount'];

                //商品描述，可空
                $body = $_POST['WIDbody'];

                //超时时间
                $timeout_express="1m";

                $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
                $payRequestBuilder->setBody($body);
                $payRequestBuilder->setSubject($subject);
                $payRequestBuilder->setOutTradeNo($out_trade_no);
                $payRequestBuilder->setTotalAmount($total_amount);
                $payRequestBuilder->setTimeExpress($timeout_express);

                $payResponse = new \AlipayTradeService($config);
                $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
                return ;
            }
        }else{
            return view();
        }
    }

    //支付成功跳转
    public function pay_success(){
        return '支付成功';
    }

    //支付回调
    public function pay_notify(){
        //file_put_contents('alipay_log.txt',time(), FILE_APPEND);   //调试日志
        require_once(ALIPAY_DEMO_PATH.'./config.php');
        require_once(ALIPAY_DEMO_PATH.'wappay/service/AlipayTradeService.php') ;

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */


        if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序			
            //注意：
            //付款完成后，支付宝系统发送该交易状态通知
             
            if($pay_type =  '1'){
                ///
            }else if($pay_type =  '2'){

            }else{

            }
                 
            echo "success";		//请不要修改或删除
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
       
       

        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //file_put_contents('alipay_log.txt', $_POST, FILE_APPEND);   //调试日志
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


                if($_POST['trade_status'] == 'TRADE_FINISHED') {

                    //判断该笔订单是否在商户网站中已经做过处理
                        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                        //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                        //如果有做过处理，不执行商户的业务程序
                            
                    //注意：
                    //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                }
                else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理
                        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                        //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                        //如果有做过处理，不执行商户的业务程序			
                    //注意：
                    //付款完成后，支付宝系统发送该交易状态通知
                }
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                    
                echo "success";		//请不要修改或删除
                    
            }else {
                //验证失败
                echo "fail";	//请不要修改或删除

            }
    }
}
