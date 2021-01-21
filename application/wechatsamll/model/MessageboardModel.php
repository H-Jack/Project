<?php

namespace app\wechatsamll\model;

use think\Model;

class MessageboardModel extends Model
{
    protected $table = 'wechat_messageboard';
    protected $autoWriteTimestamp = 'datetime';
    public function _update($data)
    {
        if(isset($data['id'])){
            return $this->save($data,['id'=>$data['id']]);
        }else{
            return $this->save($data);
        }
    }
}
