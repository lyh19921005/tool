<?php

namespace Luyh\Wechat;

use Exception;
use Luyh\Wechat\Tool\MsgCrypt;

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
            throw new Exception('参数错误');
        }
        $param = 'corpid=' . $corpid . '&corpsecret=' . $corpsecret;
        $result = json_decode(http_get(self::GET_TOKEN_URL.'?'.$param), true);
        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取token失败！');
        }

        //调整

    }

    /**
     * 获取群列表
     * @param $access_token
     * @param int $status_filter
     * @param array $userid_list
     * @param string $cursor
     * @param int $limit
     * @return mixed
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/21 14:58
     */
    public static function getWeChatGroupList($access_token,$status_filter = 0,$userid_list = [],$cursor = '',$limit = 100)
    {
        if (!$access_token) {
            throw new Exception('参数错误');
        }
        $url = self::WECHAT_GROUP_LIST . '?access_token=' . $access_token;
        $data['limit'] = $limit;
        $data['status_filter'] = $status_filter;
        if ($userid_list){
            $data['owner_filter'][] = ['userid_list'=>$userid_list];
        }
        if ($cursor){
            $data['cursor'] = $cursor;
        }
        $result = json_decode(http_post($url, $data), true);
        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取群列表失败！');
        }

    }

    /**
     * 通过群id获取群详情
     * @param $access_token
     * @param $chat_id
     * @param int $need_name
     * @return mixed
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/21 15:01
     */
    public static function getWeChatGroupDetailById($access_token, $chat_id, $need_name = 0)
    {
        if (!$access_token || !$chat_id) {
            throw new Exception('参数错误');
        }
        $data['chat_id'] = $chat_id;
        $data['need_name'] = $need_name;
        $url = self::WECHAT_GROUP_DETAIL . '?access_token=' . $access_token;
        $result = json_decode(http_post($url, $data), true);

        if (isset($result) && $result['errcode'] == 0) {
            return $result;
        } else {
            throw new Exception(isset($result['errcode']) ? $result['errmsg'] : '获取群列详情失败！');
        }

    }

    /**
     * 验证回调URL的有效性
     * @param $token
     * @param $encodingAesKey
     * @param $corpId
     * @param $msg_signature
     * @param $timestamp
     * @param $nonce
     * @param $echostr
     * @return string
     * @throws Exception
     * @author luyh 17638567762@163.com
     * @time 2021/12/23 14:02
     */
    public static function verifyUrl($token, $encodingAesKey, $corpId, $msg_signature,$timestamp,$nonce,$echostr)
    {
        $sEchoStr = "";
        $wxcpt = new MsgCrypt($token,$encodingAesKey,$corpId);
        $errCode = $wxcpt->VerifyURL($msg_signature,$timestamp,$nonce,$echostr,$sEchoStr);
        if ($errCode == 0) {
            return $sEchoStr;
        } else {
            throw new Exception('验证失败.code码是', $errCode.'生成的字符串是'.$sEchoStr);
        }
    }



}
