<?php

namespace app\wechatsamll\model;

use think\Model;

class SmallProgramIndexModel extends Model
{
    protected $table = 'wechatsmall_programindex';
    public function _update($data)
    {
        if(isset($data['id'])){
            return $this->save($data,['id'=>$data['id']]);
        }else{
            return $this->save($data);
        }
    }
}
