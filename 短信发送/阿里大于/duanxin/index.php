<?php
include 'TopSdk.php';

$c = new TopClient;
//
$appkey = '23552999';
$secret = 'd5a74afb84c45b4ad14de21683ff2674';
$signStr = 'XXX网';
$code = 'SMS_75910027';


$appkey2 = '1023831302';
$secret2 = 'sandbox0312e2ba1801c79a16cfc32fd';
$code2 = 'SMS_585014';

$recNum = '13903811902';
//

$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend("123456");
$req->setSmsType("normal");
$req->setSmsFreeSignName($signStr);
$req->setSmsParam("{\"request_id\":\"1234\",\"code\":\"5678\"}");
$req->setRecNum($recNum);
$req->setSmsTemplateCode($code);
$resp = $c->execute($req);

header("Content-type: text/html; charset=utf-8");  
var_dump($resp);