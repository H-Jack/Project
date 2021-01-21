<?php

namespace app\api\model;

use think\Model;

class WchatOrdertimeModel extends Model
{
    protected $table = 'wechat_ordertime';

    public function index($tid)
    {
        $today = date('Y-m-d', strtotime(date('Y-m-d', time())));           //获取今天的时间
        $date = \Db::table('wechat_ordertime')->field('tid', $tid)->where('date', '<', $today)->select();   //小于今天的时间
        for ($yesday = 0; $yesday < count($date); $yesday++) {                    //删除昨天之前的数据（包括昨天）
            \Db::table('wechat_ordertime')->where('id', '=', $date[$yesday]['id'])->delete();
        }
        $result = $this->where('tid', $tid)->order('date')->order('timeorder')->select();
        return $result;
    }

    public function getStatusAttr($value)   //获取器获取状态
    {
        $arr = [1 => true, 0 => false];
        return $arr[$value];
    }

    /**
     * 支付成功更改预约成功状态
     * @param $ifsubscribe 预约时间号
     */
    public static function ChangeAppointmentstatus($ifsubscribe)
    {
        //通过老师id获取老师历史预约量 进行+1
        $teachersyno = new TeacherSyno;
        $ordertimetotal = $teachersyno->where('id', $teacherid)->value('ordertimetotal');
        //更新预约量
        $teachersyno->where('id', $teacherid)->update(['ordertimetotal' => $ordertimetotal + "1"]);
        self::where('id', $ifsubscribe)
            ->update(['status' => 0]);
    }

}
