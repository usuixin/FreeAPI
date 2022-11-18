<?php
/***
 * @nick 优客
 * @author 优客API
 * @link https://api.iyk0.cn
 * @date ——
 * @域名拦截检测
***/
header('Content-type:application/json; charset=utf-8');
$url=$_GET["url"];
if($url){
    echo qq($url);
}else{
    $ret_json["code"]=201;
    $ret_json["url"]=$url;
    $ret_json["msg"]="url不能为空！";
    $ret_json['tips']="优客：https://api.iyk0.cn";
    echo ret_json($ret_json);
}




function vx($url){
    $data=get_curl("https://mp.weixinbridge.com/mp/wapredirect?url=http://".$url,0,0,0,1,"Mozilla/5.0 (Linux; Android 12; V2049A Build/SP1A.210812.003; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/86.0.4240.99 XWEB/3263 MMWEBSDK/20220204 Mobile Safari/537.36 MMWEBID/3248 MicroMessenger/8.0.20.2100(0x28001439) Process/toolsmp WeChat/arm64 Weixin NetType/WIFI Language/zh_CN ABI/arm64");
    preg_match('/Location: https:\/\/(.*?)\/cgi-bin/',$data,$key);
    
    if($key[1]=="weixin110.qq.com"){
        return "拦截";
    }else{
        return "正常";
    }


}

function qq($url){
    $url = 'https://cgi.urlsec.qq.com/index.php?m=check&a=check&callback=jQuery172003766602530889873_'.time().'&url='.$url.'&_='.$time;//请求地址
    $referer = 'https://guanjia.qq.com/online_server/result.html?url='.$url.'&=';//需要模拟来源
    $Data = get_curl($url,0,$referer);
    preg_match("/\((.*?)\)/",$Data,$key);
    $json=json_decode($key[1], true);
    if($json["reCode"]=="-101"){
        $ret_json["code"]=201;
        $ret_json["url"]=$url;
        $ret_json["msg"]="获取信息失败！";
        $ret_json['tips']="优客API：https://api.iyk0.cn";
        return ret_json($ret_json);
    }
    $url=$json["data"]["results"]["url"];
    $wtype=$json["data"]["results"]["whitetype"];
    $etype=$json["data"]["results"]["eviltype"];
    if($wtype=="1"&&$etype=="0"){
        $ret_json["code"]=200;
        $ret_json["url"]=$url;
        $ret_json["qq_msg"]="正常";
        $ret_json["vx_msg"]=vx($url);
        if($json["data"]["results"]["isDomainICPOk"]=="1"){
            $ret_json["icp_name"]=$json["data"]["results"]["Orgnization"];
            $ret_json["icp"]=$json["data"]["results"]["ICPSerial"];
        }
        $ret_json['tips']="优客API：https://api.iyk0.cn";
        return ret_json($ret_json);
    }elseif($wtype=="1"&&$etype>="0"){
        $ret_json["code"]=200;
        $ret_json["url"]=$url;
        $ret_json["qq_msg"]="报白";
        $ret_json["vx_msg"]=vx($url);
        if($json["data"]["results"]["isDomainICPOk"]=="1"){
            $ret_json["icp_name"]=$json["data"]["results"]["Orgnization"];
            $ret_json["icp"]=$json["data"]["results"]["ICPSerial"];
        }
        $ret_json['tips']="优客API：https://api.iyk0.cn";
        return ret_json($ret_json);
    }else{
        $ret_json["code"]=200;
        $ret_json["url"]=$url;
        $ret_json["qq_msg"]="拦截";
        $ret_json["vx_msg"]=vx($url);
        $ret_json["cause"]=$json["data"]["results"]["Wording"];
        if($json["data"]["results"]["isDomainICPOk"]=="1"){
            $ret_json["icp_name"]=$json["data"]["results"]["Orgnization"];
            $ret_json["icp"]=$json["data"]["results"]["ICPSerial"];
        }
        $ret_json['tips']="优客API：https://api.iyk0.cn";
        return ret_json($ret_json);
    }
    
    
}



function ret_json($json){
    return stripslashes(json_encode($json,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}


function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Accept:*/*";
	$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	if($ua){
		curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	}else{
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 12; V2049A Build/SP1A.210812.003; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/86.0.4240.99 XWEB/3263 MMWEBSDK/20220204 Mobile Safari/537.36 MMWEBID/3248 MicroMessenger/8.0.20.2100(0x28001439) Process/toolsmp WeChat/arm64 Weixin NetType/WIFI Language/zh_CN ABI/arm64');
	}
	if($nobaody){
		curl_setopt($ch, CURLOPT_NOBODY,1);
	}
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
?>
