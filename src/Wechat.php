<?php

namespace Luyh\Tool;
require_once 'func.php';

use Exception;
use Luyh\Tool\Wechat\MsgCrypt;


class Wechat
{
    const GET_TOKEN_URL = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";
    const WECHAT_GROUP_LIST = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/groupchat/list";
    const WECHAT_GROUP_DETAIL = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/groupchat/get";

    /**
     * 获取token值
     * @param string $corpid 企业id
     * @param string $corpsecret 应用秘钥
     * @return mixed
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/21 14:31
     */
    public static function getAccessToken($corpid, $corpsecret)
    {
        if (!$corpid || !$corpsecret) {
            throw new Exception('参数错误', 403);
        }
        $param = 'corpid=' . $corpid . '&corpsecret=' . $corpsecret;
        $result = json_decode(curl_get(self::GET_TOKEN_URL, $param), true);
        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取token失败！', 403);
        }
    }

    /**
     * 获取企业微信群列表
     * @param $access_token
     * @param $data
     * @return mixed
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/9 16:33
     */
    public static function getWeChatGroupList($access_token, $data)
    {
        if (!$access_token) {
            throw new Exception('参数错误');
        }
        $url = self::WECHAT_GROUP_LIST . '?access_token=' . $access_token;
        $data['limit'] = $data['limit'] ?? 100;
        $result = json_decode(curl_post($url, json_encode($data)), true);
        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取群列表失败！');
        }

    }

    /**
     * 获取群详情
     * @param $access_token
     * @param $chat_id
     * @param int $need_name
     * @return mixed
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/13 14:25
     *
     */
    public static function getWeChatGroupDetailById($access_token, $chat_id, $need_name = 1)
    {
        if (!$access_token || !$chat_id) {
            throw new Exception('参数错误');
        }
        $data['chat_id'] = $chat_id;
        $data['need_name'] = $need_name;
        $url = self::WECHAT_GROUP_DETAIL . '?access_token=' . $access_token;
        $result = json_decode(curl_post($url, json_encode($data)), true);

        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取群列详情失败！');
        }

    }

    /**
     * 验证url
     * @param $token
     * @param $encodingAesKey
     * @param $corpId
     * @param array $request
     * @author luyh 17638567762@163.com
     * @time 2021/12/16 17:34
     */
    public static function verifyUrl($token, $encodingAesKey, $corpId, $msg_signature,$timestamp,$nonce,$echostr)
    {

        $sEchoStr = "";
        $wxcpt = new MsgCrypt($token, $encodingAesKey, $corpId);
        $errCode = $wxcpt->VerifyURL(Urldecode($msg_signature), Urldecode($timestamp), Urldecode($nonce),
            Urldecode($echostr), $sEchoStr);
        if ($errCode == 0) {
            // 验证URL成功，将sEchoStr返回
            return $sEchoStr;
        } else {
            throw new Exception('验证失败.code码是'.$errCode);
        }

    }

    /**
     * 解密回调信息返回数组
     * @param $token
     * @param $encodingAesKey
     * @param $corpId
     * @param $request
     * @param $postData
     * @return array
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/18 15:00
     */
    public static function decryptMsg($token,$encodingAesKey,$corpId,$request,$postData)
    {
        $sMsg = "";
        $wxcpt = new MsgCrypt($token, $encodingAesKey, $corpId);
        $errCode = $wxcpt->DecryptMsg($request['msg_signature'],$request['timestamp'],$request['nonce'],$postData,$sMsg);
        if ($errCode == 0) {
            $arr = simplexml_load_string($sMsg,'SimpleXMLElement',LIBXML_NOCDATA);
            $arr = json_decode(json_encode($arr),true);
            return array_map('trim',$arr);
        }else {
            throw new Exception('验证失败.code码是'. $errCode);
        }
    }


}
