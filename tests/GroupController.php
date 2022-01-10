<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Luyh\Tool\Wechat;

class GroupController extends Controller
{
    //
    public function getToken()
    {
        $crop_id = 'ww86aee0c76381dd4c';
        $secret = '66LRpiGcaIbfwkG7eGY2Wq-qzrASoN5Ewk6rF6Cda74';
        return Wechat::getAccessToken($crop_id, $secret);
    }

    public function getGroupList()
    {
        $token = "HtPtgHIwxpAu_yZ516sfuk9s3_Nefw1zLVbPn-Zc77Efs9GF0Rpdb33Bl8DWT9UF7cyQNgzvmkTzg1wgJojxSIRKfO4cJlnFVH3-UMI8XM3LWT6hqRlYzZWp8g0xFvMYOeQAXd8XlBJQ1kjDAa2Yze8QqdvNCPR_xneIqsc1ll2L0iZyBPrW8Nd2U6SDKt00xzkh1svrTkVCNfhRuECH9w";
        $data = [];
        return Wechat::getWeChatGroupList($token,$data);
    }

    public function getWeChatGroupDetailById()
    {
        $token = "HtPtgHIwxpAu_yZ516sfuk9s3_Nefw1zLVbPn-Zc77Efs9GF0Rpdb33Bl8DWT9UF7cyQNgzvmkTzg1wgJojxSIRKfO4cJlnFVH3-UMI8XM3LWT6hqRlYzZWp8g0xFvMYOeQAXd8XlBJQ1kjDAa2Yze8QqdvNCPR_xneIqsc1ll2L0iZyBPrW8Nd2U6SDKt00xzkh1svrTkVCNfhRuECH9w";
        $chat_id = "wr80maBwAAT6yBF7pQH2KSDbhyOObN_A";
        return Wechat::getWeChatGroupDetailById($token,$chat_id);

    }
}
