<?php



namespace app\api\model;


use think\Model;

class WechatBlogModel extends Model
{
    protected $table = 'wechat_blog';

    public function index($classid)
    {
        $list = $this->where("videoid",$classid)
        ->order("create_time" ,"desc")
        ->limit(30)
        ->select();  //创建时间倒序select出数据
        return $list;
    }
}