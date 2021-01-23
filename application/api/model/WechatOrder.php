<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/21
 * Time: 14:12
 *
 *    .--,       .--,
 *   ( (  \.---./  ) )
 *    '.__/o   o\__.'
 *       {=  ^  =}
 *        >  -  <
 *       /       \
 *      //       \\
 *     //|   .   |\\
 *     "'\       /'"_.-~^`'-.
 *        \  _  /--'         `
 *      ___)( )(___
 *     (((__) (__)))    高山仰止,景行行止.虽不能至,心向往之.
 *
 */


namespace app\api\model;

use app\api\model\CourseColumn;

class WechatOrder extends Base
{
    protected $table = 'wechat_order';

    /**
     * 通过商户订单查询订单是否存在
     * @param $out_trade_no
     * @return WechatOrder
     */
    public static function Getorder($out_trade_no)
    {
        $list = self::where('out_trade_no',$out_trade_no)->find();
        $cour = new CourseColumn;
        //获取预约总量
        $num = $cour->where('id',$list->course_num)->value('studentsnumber');
        //购买成功课程学习人数+1
        $cour->where('id', $list->course_num)->update(['studentsnumber' => $num + "1"]);
        return $list;
    }

    /**
     * 更新支付完成后的订单
     * @param $arr
     *
     */
    public static function insertorder($arr)
    {
        $list = self::where('out_trade_no',$arr['out_trade_no'])->find();
        $price = $arr['total_fee']/100;
        if($list->total_price != $price){
            exit('支付价格不正确');
        }
        self::where('id',$list->id)
            ->update(['status'=>1,'transaction_id'=>$arr['transaction_id']]);
    }

    /**
     * 返回给小程序的全部订单
     */
    public static function returnallorders()
    {
        $list = self::field('out_trade_no,total_price,snap_name,status,update_time,subscribe_num,course_num,id')
            ->where('status','1')
            ->select();
        return $list;
    }

    /**
     * 查询subscribe_num字段是否为空
     */
    public static function returnsubscribe($outorder)
    {
        $list = self::field('appointmentid,subscribe_num')->where('out_trade_no',$outorder)->find();
        if(!$list)
            return false;
        return $list;
    }

    /**
     * 返回小程序预约订单
     */
    public static function Returntobookingorder($id)
    {
        $list = self::where('uid',$id)
            ->where('subscribe_num','not null')
            ->where('status','1')
            ->select();
        return $list;
    }
}