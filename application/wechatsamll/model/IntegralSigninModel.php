<?php

namespace app\wechatsamll\model;

use think\Model;

class IntegralSigninModel extends Model
{
    protected $table = 'wechat_signin';
    protected $autoWriteTimestamp = 'datetime';
    public function _update($data)
    {
        if(isset($data['id'])){
            return $this->save($data,['id'=>$data['id']]);
        }else{
            return $this->save($data);
        }
    }

    public function _updateStatus($data,$id)
    {
        $result = $this->where('id', $id)->find();
        $result->save([
            'status' => $data
        ]);
    }

}
