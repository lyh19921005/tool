<?php
if (!function_exists('curl_post')) {
    /**
     * 统一CURL请求
     * @param  string    $url
     * @param null $data post数据
     * @param array $headers header头
     * @param int $tiemout 超时时间秒数
     * @return bool|string
     * @throws Exception
     * @author 牛永光 nyg1991@aliyun.com
     * @date   2020/1/2 17:59
     */
    function curl_post($url, $data = null,$headers=[],$tiemout=0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if($tiemout){
            curl_setopt($ch, CURLOPT_TIMEOUT, $tiemout); //设置超时秒数
        }
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);//返回response头部信息
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception("Curl error: " . curl_errno($ch));
        }
        curl_close($ch);
        return $info;
    }
}



if(!function_exists('curl_get')){
    /**
     * get请求
     * @param $url
     * @param false $params
     * @param int $https
     * @return bool|string
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/8 11:44
     */
    function curl_get($url, $params = false, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在


        if ($params) {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);// 此处就是参数的列表,给你加了个?
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            throw new Exception(curl_error($ch));
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}








if(!function_exists('send_sms')){
    /**
     * 云片短信发送
     * @param $mobile
     * @param $msg
     * @param $apikey
     * @return bool
     */
    function send_sms($mobile,$msg,$apikey){
        // 发送短信
        $data = ['text' => $msg, 'apikey' => $apikey, 'mobile' => $mobile];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://yunpian.com/v1/sms/send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json_data = curl_exec($ch);
        $res = json_decode($json_data, true);
        if (0 != $res['code']) {
            return false;
        } else {
            return true;
        }
    }
}

if(!function_exists('check_mobile')){
    /**
     * 验证手机号
     * @param $mobile
     * @return false|int
     */
    function check_mobile($mobile){
        return preg_match("/^1\d{10}$/", $mobile);
    }
}

if(!function_exists('zzpay_encrypt')){
    /**
     * 支付中心加密方式
     * @param $data
     * @param $deskey
     * @return string
     */
    function zzpay_encrypt($data,$deskey){
        $data = array_filter($data, 'strlen');
        ksort($data);
        $json=json_encode($data);
        return base64_encode(openssl_encrypt(pkcs5_pad($json, 8), "DES-EDE3",
            $deskey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING));
    }
}

if (!function_exists('pkcs5_pad')) {
    function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}

if(!function_exists('yj_encrypt')){
    /**
     * 一家加密方式
     * @param $data
     * @param $key
     * @param string $method
     * @param string $iv
     * @param int $option
     * @return false|string
     */
    function yj_encrypt($data,$key,$method='AES-256-ECB',$iv='',$option=0){
        return openssl_encrypt($data, $method, $key, $option, $iv);
    }
}

if(!function_exists('yj_getUserInfo')){
    /**
     * 获取一家App+用户信息
     * @param $accessToken
     * @param $url
     * @param $key
     * @return bool|mixed|string
     */
    function yj_getUserInfo($accessToken, $url, $key)
    {
        $url=$url.'game/user/getuserinfo.json';//获取用户信息
        $str = yj_encrypt($accessToken, $key);
        $str = str_replace("+", "****", $str);
        $postData = array("accesstokenstr" => $str, "accesstoken" => $accessToken);
        $getUserInfo = curl_post($url, $postData);
        $getUserInfo = json_decode($getUserInfo, true);
        if ($getUserInfo['rtncode'] != '00') {
            return false;
        }
        return $getUserInfo;
    }
}

if (!function_exists('yj_accountInfo')) {
    /**
     * 建业+获取帐号信息
     * @param $accessToken
     * @param $url
     * @return bool|string
     */
    function yj_accountInfo($accessToken, $url)
    {
        $url = $url . 'app/user/accountinfo.json';//获取账号信息
        $getToken['accessToken'] = $accessToken;
        $getUserInfo = curl_post($url, $getToken);
        $getUserInfo = json_decode($getUserInfo, true);
        if ($getUserInfo['code'] != '100') {
            return false;
        }
        return $getUserInfo['data'];
    }
}

if (!function_exists('array_only')) {

    /**
     * 取出数组中的部分数据
     * @param array $array
     * @param array $keys
     * @return array
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/5/4 11:55
     */
    function array_only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }
}