<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/20
 * Time: 17:00
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


namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\model\WechatOrder;
use app\api\validate\TeacherSynolist;
use app\lib\exception\CorrectException;
use app\lib\exception\PointsException;
use app\lib\exception\WechatisException;
use app\lib\exception\WechatpayException;
use EasyWeChat\Factory;
use think\Request;
use think\Facade;
use function Sodium\add;

class WechatPayment extends  Base
{
    public $ifmakeanappointment = null;
    /**
     * 支付接口
     */
    public function pay()
    {
        $config = [
            // 必要配置
            'app_id'             => config('wechat.app_id'),
            'mch_id'             => config('wechat.mch_id'),
            'key'                => config('wechat.keys'),   // API 密钥
            'notify_url'         => 'http://yxeih2.natappfree.cc/index.php/wechat/api/notify',     // 你也可以在下单时单独设置来想覆盖它
        ];
        $app = Factory::payment($config);
        $validate = new TeacherSynolist;
        $inputarr = json_decode(input()['data']);
        //获取老师id
        $tid = $inputarr[0];
        //获取课程描述
        $name = $inputarr[1];
        //判断获取的id
        $id = ['id'=>$tid];
        //获取预约id，用于预约
        $cid = null;
        if(!empty($inputarr[3]) || !empty($inputarr[4]))
            $cid = $inputarr[3];
        //判断是否为预约
        if(count($inputarr)>=3 &&$inputarr[2] == '2')
            $this->ifmakeanappointment = $inputarr[2];

        //用户openid
        $openid = $this->Finduseropenid($this->id);
        //推荐人用户ID
        $recommendId = $this->FindUserRecommendId($this->id);

        if(!$validate->scene('searchteacher')->check($id))
            throw new CorrectException();

        $arr = $this->createOrder($tid,$name,$cid);

        $attributes = [
            'body' => $arr['snap_name'],
            'out_trade_no' => $arr['out_trade_no'],//保证唯一性，不然不能支付
            'total_fee' => $arr['price'],
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $openid,
        ];

        //判断是否有推荐人，有推荐人，则设置该笔订单进行分账
        if(count($inputarr)<3 && empty($inputarr[2]))
            if ($recommendId && $recommendId != 0) {
                $attributes['profit_sharing'] = 'Y';
            }

        //创建预订单
        $result = $app->order->unify($attributes);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS')
        {
            $prepayId = $result['prepay_id'];
            $jssdk = $app->jssdk;
            $config = $jssdk->sdkConfig($prepayId);
            $this->success(compact('config'), 1, "支付");//返回前端json数据
        }
        if ($result['return_code'] == 'FAIL' && array_key_exists('return_msg', $result))
        {
            throw new WechatpayException();
        }
        throw new WechatisException();

    }

    /**
     * 积分兑换支付
     */
    public function PayPointsexchange()
    {
        $fruit = false;
        //课程id
        $integralid = input('post.id');
        //判断是否购买过
        $cour_class = new \app\api\controller\v1\CourseColumn;
        $cour_result = $cour_class->Judgewhetherusersbuycourses($integralid);
        if($cour_result)
            return false;

        //获取课程多少积分
        $resute = \app\api\model\CourseColumn::personalist($integralid);
        if($resute->coursepoints == 0)
            return false;
        //课程积分
        $cour_integral = $resute->coursepoints;
        //课程支付描述
        $snap_name = json_decode(input('post.data'))[0];
        //课程价钱
        $cour_price = $resute->price;
        //获取用户有多少积分
        $user_integral = \app\api\model\WechatUser::returnintegral($this->id);
        //判断用户积分有没有大于课程兑换积分
        if($user_integral >= $cour_integral)
            //执行扣除积分，并插入订单
            $fruit = $this->Deductionofpoints($cour_integral,$integralid,$cour_price,$snap_name);

        return $fruit;

    }

    /**
     * 执行扣除积分，并插入订单
     * $cour_integral 课程积分
     * $integral 课程id
     * $cour_price 课程价钱
     */
    private function Deductionofpoints($cour_integral,$integralid,$cour_price,$snap_name)
    {
        //扣除用户积分
        $data = \app\api\model\WechatUser::delintegral($this->id,$cour_integral);
        //判断是否扣除成功
        if(!$data)
            throw new PointsException();
        //插入订单
        $orderNo = new \app\api\model\WechatOrder();
        $orderNo->uid = $this->id;
        $orderNo->out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $orderNo->course_num = $integralid;
        $orderNo->status = 1;
        $orderNo->total_price = $cour_price;

        //代写订单详细
        $orderNo->snap_name = $snap_name;

        $orderNo->save();
        return $this->success('购买成功');

    }

    /**
     * 创建并写入订单号
     * @param $cid  id
     * @param $name  描述课程文字
     * @return array
     */
    private function createOrder($tid,$name,$cid)
    {
        $price = $this->Findcourseprice($tid,$cid);
        $orderNo = new \app\api\model\WechatOrder();
        $orderNo->uid = $this->id;
        $orderNo->out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $orderNo->snap_name = $name;
        //判断是否为预约，字段不同
        if($this->ifmakeanappointment == '2') {
            $orderNo->total_price = $price['money'];
            $orderNo->subscribe_num = $tid;  //插入老师id
            $orderNo->appointmentid = $cid;  //未写
        }else{
            $orderNo->total_price = $price['price'];
            $orderNo->course_num = $tid;
        }

        $orderNo->save();
        return [
            'out_trade_no' => $orderNo->out_trade_no,
            'uid'           => $orderNo->uid,
            'price'         => $orderNo->total_price * 100,
            'snap_name'     => $name,
        ];
    }

    /**
     * 查找课程价钱
     */
    private function Findcourseprice($tid,$cid)
    {
        if($this->ifmakeanappointment == 2){
            $course = new  \app\api\model\WchatOrdertimeModel();
            return $course->field('money')->where('id',$cid)->find()->toArray();
        }else{
            $course = new \app\api\model\CourseColumn();
            return $course->field('price')->find($tid)->toArray();
        }
    }

    /**
     * 查找用户的openid
     */
    private function Finduseropenid($id)
    {
        $result = new \app\api\model\WechatUser();
        return $result->where('id',$id)->value('openid');
    }

    private function FindUserRecommendId($id)
    {
        $result = new \app\api\model\WechatUser();
        return $result->where('id',$id)->value("recommend_id");
    }
}