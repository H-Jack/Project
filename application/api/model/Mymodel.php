<?php



namespace app\api\model;


use think\Model;

class Mymodel extends Model
{
    protected $table = 'wechat_user';
    public function _update($data,$id)
    {
        if(isset($id)){
            return $this->save($data,['id'=>$id]);
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