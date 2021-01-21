<?php


namespace app\wechatsamll\controller;

use app\wechatsamll\model\DistributionConfig;
use library\Controller;
use think\Db;
use think\Request;

/**
 * 分销栏目管理
 * Class DistributionBill
 * @package app\wechatsamll\controller
 */
class DistributionBill extends Controller
{

    /**
     * 绑定当前数据表
     * @var string
     */
    protected $table = 'WechatDistributionBill';
    protected $table2 = 'WechatDistributionConfig';

    /**
     * 表单结果处理
     * @auth true
     */
    public function save(Request $request)
    {
        $data = $request->param();
        if ($data['course_val'] >= 1 || $data['course_val'] <= 0 || $data['advisory_val'] >= 1 || $data['advisory_val'] <= 0) {
            return ['code'=>0, 'msg'=>'请填写小于1，大于0的两位小数'];
        }
        $db = new DistributionConfig();
        $res = $db->_update($data);
        if($res){
            return ['code'=>1, 'msg'=>'操作成功'];
        }else{
            return ['code'=>0, 'msg'=>'操作失败'];
        }
    }

    /**
     * @auth true
     * @menu true
     * 进入分销记录首页
     */
    public function index()
    {
        $this->title = '分销记录';
        $query = $this->_query($this->table)->like('');
        $query->dateBetween('create_at')->order('id desc')->page();

    }

    /**
     * @auth true
     * 设置分销率
     */
    public function setRate()
    {
        $this->title = '设置分销率';
        $rate = Db::table("wechat_distribution_config")->where('type', 'rate')->find();
        $this->_form($this->table2, 'form', "", [], ['course_val' => $rate['course_val'], 'advisory_val' => $rate['advisory_val'], 'id' => $rate['id']]);
    }

}