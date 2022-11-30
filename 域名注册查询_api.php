<?php
/***
 * @nick 优客
 * @author 优客API
 * @link https://api.iyk0.cn
 * @date ——
 * @域名注册查询
***/
header('Content-type:application/json; charset=utf-8');
$domain = $_GET["domain"];

if (empty($domain)) {
    exit(msg(400, "参数domain值不能为空，示例：domain=iyk0.cn"));

}

$str = curl_get("http://panda.www.net.cn/cgi-bin/check.cgi?area_domain=".$domain);
preg_match("/<original>(.*?) :/i", $str, $mat);
//var_dump($mat);
if (!$mat) {
    exit(msg(500, "查询失败"));
}
if ($mat[1] == 210) {
    exit(msg(200, "未注册"));
}
if ($mat[1] == 211) {
    exit(msg(200, "已注册"));
}

function randIp() {
    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
}
function curl_get($url) {
    $header = [
        'X-FORWARDED-FOR:'.randIp(),
        'CLIENT-IP:'.randIp()
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36"); // 伪造ua
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
