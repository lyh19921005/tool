## 使用方法

### 安装扩展
> composer require luyh/tool

### 使用方法
``` php

use Luyh\Tool\Wechat;


//获取token
//crop_id 企业微信中企业id
//corpsecret应用秘钥
Wechat::getAccessToken($crop_id,$corpsecret)

//获取群列表
//参数内容见微信官方文档(https://work.weixin.qq.com/api/doc/90000/90135/92120)
Wechat::getWeChatGroupList($access_token,$status_filter = 0,$userid_list = [],$cursor = '',$limit = 100)

//获取群详情
//参数内容见微信官方文档(https://work.weixin.qq.com/api/doc/90000/90135/92122)
Wechat::getWeChatGroupDetailById($access_token, $chat_id, $need_name = 0)

//验证回调URL的有效性
//$token通过token方法获取的值
//$encodingAesKey 应用秘钥
//$corpId 企业id
参数内容见微信官方文档(https://work.weixin.qq.com/api/doc/90000/90135/90238#%E9%AA%8C%E8%AF%81URL%E6%9C%89%E6%95%88%E6%80%A7)
Wechat::verifyUrl($token, $encodingAesKey, $corpId, $msg_signature,$timestamp,$nonce,$echostr)

//解密回调数据,返回数组

wechat::decryptMsg($token,$encodingAesKey,$corpId,$msgSignature,$timestamp,$nonce,$postData)




```

