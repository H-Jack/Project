<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/20
 * Time: 20:58
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


namespace app\api\controller;

use app\lib\exception\OrdernotexistException;
use app\wechat\controller\Distribution;
use EasyWeChat\Kernel\Support\XML;
use think\Collection;
use think\Db;
use think\Exception;
use think\Request;
use EasyWeChat\Factory;
use think\Facade;

class WechatUnify
{
    /**
     * 微信支付回调方法
     * @param Request $request
     * @throws Exception
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function notify(Request $request)
    {
        //返回支付参数
        $notify = $this->getNotify($request)->toArray();
        //判断支付金额，插入微信生成的订单
        \app\api\model\WechatOrder::insertorder($notify);

        $config = [
            // 必要配置
            'app_id'             => config('wechat.app_id'),
            'mch_id'             => config('wechat.mch_id'),
            'key'                => config('wechat.keys'),   // API 密钥
            'notify_url'         => 'http://ja5e9u.natappfree.cc/index.php/wechat/api/notify',     // 你也可以在下单时单独设置来想覆盖它
        ];

        $payment = Factory::payment($config);
        $response = $payment->handlePaidNotify(function ($message, $fail) {
            //查询订单 和 购买成功课程学习人数+1 功能
            $order = \app\api\model\WechatOrder::Getorder($message['out_trade_no']);
            //查询是否为预约订单
            $ifsubscribe = \app\api\model\WechatOrder::returnsubscribe($message['out_trade_no'])->toArray();
            if($ifsubscribe)
                \app\api\model\WchatOrdertimeModel::ChangeAppointmentstatus($ifsubscribe['appointmentid'],$ifsubscribe['subscribe_num']);
            //订单不存在
            if (!$order)
                throw new OrdernotexistException();

            //用户支付失败
            if($message['result_code'] === 'FAIL')
                exit('用户支付失败');

            if($message['result_code'] === 'SUCCESS') {
                // 支付成功后的业务逻辑


                //查询该订单是否已经分销过
                $isExist = Db::table("wechat_distribution_bill")->where("order_id", $order['id'])->find();
                if (!$isExist) {
                    $wechatDeposit = new WechatDeposit();
                    // 获取订单购买者用户信息
                    $currentUser = $this->FindUserRecommendId($order->uid);
                    // 获取推荐人用户ID
                    $recommendId = $currentUser['recommend_id'];
                    // 判断该用户是否有推荐人
                    if ($recommendId && $recommendId != 0) {
                        // 获取推荐人用户信息
                        $recommendUser = $this->FindUserOpenid($recommendId);
                        // 获取推荐人openid
                        $openid = $recommendUser['openid'];
                        // 添加分账接收方
                        $addResult = $wechatDeposit->add_sub($openid);
                        if ($addResult['return_code'] === 'SUCCESS') {
                            // 根据订单类型，查询指定的分销率。  目前暂时定死课程分销率
                            $proportion = $this->findCourseRate();
                            // 调用请求分账接口，完成分账
                            $depositResult = $wechatDeposit->sub_account($order->transaction_id, $order, $openid, $proportion);
                            if ($depositResult['result_code'] === 'SUCCESS') {
                                //微信分账成功后，把分账流水记录在distribution表中
                                $distribution = new Distribution();
                                $distribution->accounting($currentUser, $recommendUser, $order, $proportion);
                            }
                        }
                    }
                }
            }

            return true;// 返回处理完成
        });

            //返回值
        $response->send();
    }

    private function getNotify($request)
    {

        try {
            $xml = XML::parse(strval($request->getContent()));
        } catch (Exception $e) {
            throw new Exception('Invalid request XML: ' . $e->getMessage(), 400);
        }

        if (!is_array($xml) || empty($xml)) {
            throw new Exception('Invalid request XML.', 400);
        }

        return new Collection($xml);
    }

    /**
     * 查找用户的openid
     */
    private function findUserOpenid($id)
    {
        $result = new \app\api\model\WechatUser();
//        openid
        return $result->where('id',$id)->find();
    }

    private function findUserRecommendId($id)
    {
        $result = new \app\api\model\WechatUser();
//        recommend_id
        return $result->where('id',$id)->find();
    }

    private function findCourseRate()
    {
        $result = new \app\api\model\DistributionConfig();
        return $result->findCourseRate();
    }
}