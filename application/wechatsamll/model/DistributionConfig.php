<?php


namespace app\wechatsamll\model;


use think\Db;
use think\Model;

class DistributionConfig extends Model
{
    protected $table = 'wechat_distribution_config';

    public function _update($data)
    {
        if(isset($data['id'])){
            return $this->save($data,['id'=>$data['id']]);
        }else{
            $data['type'] = 'rate';
            return $this->save($data);
        }
    }
}