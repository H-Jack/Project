<?php

namespace app\common\model;

use think\Model;

class MessageModel extends Model
{
    protected $table = "wechat_messageboard";
//    public function getImgAttr($valude){        //获取img属性
//        $domain = config('app.domain');   //加上domain域名
//        return $domain.$valude;
//    }

}
