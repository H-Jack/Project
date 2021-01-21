<?php


namespace app\wechat\controller;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\facade\Request;
/**
 * 分销管理
 * Class Distribution
 * @package app\wechat\controller
 */
class Distribution
{

    /**
     * 显示我的分销
     */
    public function showMyself($uid)
    {
        $data = Db::table("wechat_distribution_bill")->where('uid', $uid)->select();
        return json($data);
    }

    public function bind($uid, $puid)
    {
        //TODO 根据uid查询微信用户，判断是否存在；
        //TODO 根据puid查询微信用户，判断是否存在；
        //TODO 根据uid查询本张表，判断是否已经绑定；
        $data = Db::table("wechat_distribution")->where('uid', $uid)->find();
        if ($data) {
            return "请勿重复绑定";

        }
        $data = [
            "uid"   =>  $uid,
            "puid"  =>  $puid,
            "p_openid"  =>  "123openid"
        ];
        Db::table("wechat_distribution")->insert($data);
        return "绑定成功";
    }

    public function accounting($user, $recommendUser, $order, $rate)
    {
//        $puid = Db::table("wechat_distribution")->where('uid', $uid)->value("puid");
        $isExist = Db::table("wechat_distribution_bill")->where("order_id", $order['id'])->find();
        if ($isExist) {
            return "该订单已核销过！";
        }

        $originPrice = $order['total_price'];
        $price = round($originPrice * $rate,2);
        //插入分销记录到分销账单wechat_distribution_bill
        $bill = [
            "uid"   =>  $user['id'],
            "uname"   =>  $user['username'],
            "puid"   =>  $recommendUser['id'],
            "puname"   =>  $recommendUser['username'],
            "price"  =>  $price,
            "origin_price"  =>  $originPrice,
            "rate"  =>  $rate,
            "order_id"  => $order['id'],
            "create_time"   =>  date('Y-m-d H:i:s')
        ];
        Db::table("wechat_distribution_bill")->insert($bill);
        return "核销成功！";
    }

    public function abc()
    {
        return view();
    }

    public function test2()
    {
        $data = Db::table("wechat_distribution")->select();
        return json($data);
    }

    public function test3()
    {
//        $data = Db::table("wechat_distribution")->find();
//        $data = Db::table("wechat_distribution")->where('uid', '222')->find();
        try {
            $data = Db::table("wechat_distribution")->where('uid', '333')->findOrFail();
        } catch (DataNotFoundException $e) {
            return "查询不到数据";
        }
        return json($data);
    }

    public function test4()
    {
        $data = [
            "uid"   =>  "666",
            "puid"  =>  "999"
        ];
        $data = Db::table("wechat_distribution")->insert($data);
        return json($data);
    }

    public function test5()
    {
        $data = [
            "uid"   =>  "666",
            "price"  =>  90.21,
            "create_time"   =>  date('Y-m-d H:i:s'),
            "order_id"  => "12345"
        ];
        $data = Db::table("wechat_distribution_bill")->insert($data);
        return json($data);
    }
}