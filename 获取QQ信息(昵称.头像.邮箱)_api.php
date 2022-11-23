<?php
/***
 * @nick 优客
 * @author 优客API
 * @link https://api.iyk0.cn
 * @date ——
 * @获取QQ信息-聚合版
***/
// header
header("Content-Type:application/json; charset=utf-8");
error_reporting(E_ALL^E_NOTICE^E_WARNING);
 
// 获取QQ号
$qq = $_GET["qq"];
 
// 过滤
if (trim(empty($qq))) {
        echo json_encode(array('code'=>'-1','status' => 'error','msg' => '未传入QQ号'),JSON_UNESCAPED_UNICODE);
}else{
        // 获取QQ用户信息
        $urlPre='http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=1518561325&uins=';
        $data=file_get_contents($urlPre.$qq);
        $data=iconv("GB2312","UTF-8",$data);
        $pattern = '/portraitCallBack\((.*)\)/is';
        preg_match($pattern,$data,$result);
        $result=$result[1];
        $qqnickname = json_decode($result, true)["$qq"][6];
        $qqheadimg = "http://q1.qlogo.cn/g?b=qq&nk=".$qq."&s=100";
        $qqmail = "".$qq."@qq.com";
 
        // 开始判断这个QQ号是不是有真实用户信息返回
        if ($qqnickname) {
                // 如果有，就可以返回JSON数据
                echo json_encode(array('code'=>'200','status' => 'success','msg' => '获取用户信息成功','nickname' => $qqnickname,'headimg' => $qqheadimg,'mail' => $qqmail),JSON_UNESCAPED_UNICODE);
        }else{
                //如果没有，那么只能返回获取失败
                echo json_encode(array('code'=>'-0','status' => 'error','msg' => '获取用户信息失败'),JSON_UNESCAPED_UNICODE);
        }
}
?>
