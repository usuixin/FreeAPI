<?php
/**
 * @nick 随心
 * @author 随心API
 * @link https://api.vvsui.com
 * @date ——
 * @获取QQ信息-code refactoring
 * @解析 头像 邮箱 网名
**/
header('content-type:application/json; charset=utf8');
if ($_GET['qq']) {
    $qq = $_GET['qq'];
    //向接口发起请求获取json数据
    $get_info = file_get_contents('http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins='.$qq);
    //转换编码
    $get_info = mb_convert_encoding($get_info, "UTF-8", "GBK");
    //对获取的json数据进行截取并解析成数组
    $name = json_decode(substr($get_info,17,-1),true);
    if($name and $qq){ 
        $server = rand(1,4);
        $txUrl = 'https://q'.$server.'.qlogo.cn/headimg_dl?dst_uin='.$qq.'&spec=100';
        $arr = array(
            'code' => 200,
            'msg' => 'success',
            'imgurl' => $txUrl,
            'mail' => "$qq@qq.com",
            'name' => urlencode($name[$qq][6])
        );
        $json_string = json_encode($arr);
        exit(stripslashes(urldecode(json_encode($arr))));
    }else{
        $arr = array(
            'code' => -1,
            'msg' => 'Error'
        );
        exit(stripslashes(urldecode(json_encode($arr))));
    }
    }else{
        $arr = array(
            'code' => -1,
            'msg' => 'Error'
        );
        exit(stripslashes(urldecode(json_encode($arr))));
    }
