## 使用方法

### 安装扩展
 > composer require luyh/wechat

 ### 使用方法
``` php

use Luyh/Wechat


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


```
