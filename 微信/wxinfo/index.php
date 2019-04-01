<?php
//author  k4u_fish
//2019-03-30

header("Content-type: text/html; charset=utf-8");

$appid = "wx8fee7512be1bb47a";
$secret = "cb747f90bf33749f1fb3e93531c5e4f9";
$redirect_uri = "http://{$_SERVER["SERVER_NAME"]}/wxinfo/index.php";   //请求到code后要进一步请求的url(本文件步骤2)
if(!isset($_GET['code'])){  //步骤1，换取code
   // $appid='wx8fee7512be1bb47a';//公众号在微信的appid
   // $REDIRECT_URI = $_SERVER["HTTP_HOST"].'/wxinfo/';   //要请求的url

    $scope='snsapi_userinfo'; //需要用户同意收授权
//    $scope='snsapi_base'; //不需要用户同意授权，access_token 有效期两小时，使用refresh_tocken 重新获取
    $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($redirect_uri)
            .'&response_type=code&scope='.$scope.'&state=wx'.'#wechat_redirect';
    header("Location:".$url);
}else{
    $code = $_GET["code"]; //步骤2，使用code换取access_token
    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$get_token_url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res,true);
    // print_r( $json_obj);
    $access_token = $json_obj['access_token'];
    $refresh_token = $json_obj['refresh_token'];
    $exprise_in = $json_obj['exprise_in'];
    $openid = $json_obj['openid'];

    //步骤3，根据openid和access_token查询用户信息
    $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    
    //解析json
    $user_obj = json_decode($res,true);
    $user_obj['access_token'] = $access_token;
    $user_obj['access_timeout'] = time() + $exprise_in;
    $user_obj['refresh_token'] = $refresh_token;
    // 后续处理
    session_start();
    //$_SESSION['wxuser'] = $user_obj;  
    $_SESSION['wxuser'] = serialize($user_obj);  //序列化写入，读取时反序列化
    print_r($_SESSION['wxuser']);
   //print_r($user_obj); //调试时可以这样写

   header("Location:http://{$_SERVER["SERVER_NAME"]}/");  //授权后跳转至相应页面
   
}