<?php


namespace app\api\controller;

use think\Controller;

/**
 * Class WechatDeposit
 * @package app\api\controller
 * @description 微信分账接口
 */
class WechatDeposit extends Controller
{
    private $addsep_receiving_url;              // 添加分账接收方请求URL
    private $addsep_receiving_type;             // 分账接收方类型 此处是个人openid类型
    private $addsep_receiving_relation_type;    // 分账关系类型
    private $mch_id;                            // 商户号
    private $appid;                             // 公众号appid
    private $mch_secrect;                       // 此处是商户key！！！

    function __construct()
    {
        $this->addsep_receiving_url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';
        $this->addsep_receiving_type = 'PERSONAL_OPENID';
        $this->addsep_receiving_relation_type = 'USER';
        $this->mch_id = config('wechat.mch_id');
        $this->appid = config('wechat.app_id');
        $this->mch_secrect = config('wechat.keys');
    }

    /**
     * Notes: 添加微信分账接收方
     * Url: 调用该方法 传入openid
     */
    public function add_sub($openid)
    {
        $tmp_receiving_data = [
            'mch_id' => $this->mch_id,
            'appid' => $this->appid,
            'nonce_str' => $this->get_nonce_str(),
            'sign_type' => 'HMAC-SHA256',
            'receiver' => $this->receiver($openid)
        ];

        $tmp_receiving_data['sign'] = $this->make_sign($tmp_receiving_data, $this->mch_secrect);
        $xml = $this->array_to_xml($tmp_receiving_data);
        $do_arr = $this->post_xml_curl($xml, $this->addsep_receiving_url);
        $result = $this->xml_to_array($do_arr);
        return $result;
    }

    /**
     * Notes: 接收方信息
     * @param $openid
     * @return false|string
     */
    private function receiver($openid)
    {
        $tmp_receiver_arr = [
            'type' => 'PERSONAL_OPENID',
            'account' => $openid,
            'relation_type' => $this->addsep_receiving_relation_type,
        ];

        return json_encode($tmp_receiver_arr);
    }

    /**
     * Notes: 获取随机数
     * @param int $length
     * @return string
     */
    private function get_nonce_str($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * Notes: 生成sign
     * @param $arr
     * @param $secret
     * @return string
     */
    private function make_sign($arr, $secret)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);
        $str = $this->to_url_params($arr);
        //签名步骤二：在str后加入KEY
        $str = $str . "&key=" . $secret;
        //签名步骤三：HMAC-SHA256 类型  加密的字符串 key是商户秘钥
        $str = hash_hmac('sha256', $str, $this->mch_secrect);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($str);
        return $result;
    }

    /**
     * Notes: 数组转字符串
     * @param $arr
     * @return string
     */
    private function to_url_params($arr)
    {
        $str = "";
        foreach ($arr as $k => $v) {
            if (!empty($v) && ($k != 'sign')) {
                $str .= "$k" . "=" . $v . "&";
            }
        }
        $str = rtrim($str, "&");
        return $str;
    }

    /**
     * Notes: 数组转XML
     * @param $arr
     * @return string
     */
    private function array_to_xml($arr){
        $xml = '<?xml version="1.0" encoding="UTF-8"?><xml>';
        foreach ($arr as $key => $val) {
            $xml.="<".$key.">$val</".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * Notes: XML转数组
     * @param $xml
     * @return mixed
     */
    private function xml_to_array($xml){
        libxml_disable_entity_loader(true);
        $arr= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    /**
     * Notes: POST 请求 此处不需要证书
     * @param $xml
     * @param $url
     * @param int $second
     * @return bool|string
     */
    private function post_xml_curl($xml, $url, $second = 30){
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //curl_close($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 请求分账接口，完成分账
     * @param $transaction_id 微信支付订单号
     * @param $order 订单信息
     * @param $openid 分账接收方Openid
     * @param $proportion 分销比例
     * @return bool
     */
    public function sub_account($transaction_id, $order, $openid, $proportion)
    {
        sleep(2);
        header("Content-Type:text/html;charset=utf8");

        $newPara = array();
        // 公众账号ID
        $newPara["appid"] = $this->appid;
        // 商户号
        $newPara["mch_id"] = $this->mch_id;
        // 随机字符串
        $newPara["nonce_str"] = $this->get_nonce_str();
        // 商户分账单号
        $out_order_no = mt_rand(100000,999999).time().mt_rand(100,999);
        $newPara["out_order_no"] = $out_order_no;

        // 微信订单号
        $newPara["transaction_id"] = $transaction_id;

        //签名类型 HMAC-SHA256
        $newPara["sign_type"] = "HMAC-SHA256";

//        $user = User::where('is_sub',1)->select(['id','open_id','proportion'])->get();
        // 分账接收方列表
        $receivers = [];
//        foreach ($user as $k => $val) {
            if($order->total_price >= 1){
                //  扣除0.6%微信手续费
                // $money = floor($order->total_price * 0.994 * $proportion); // 舍去法取整
                // $money = $order->total_price * $proportion * 100; // 舍去法取整
                $money = floor($order->total_price * $proportion * 100);
//                $money = 1;
            }else{
                $money = floor($order->total_price * $proportion * 100);

                // $money = $order->total_price * $proportion * 100; // 舍去法取整
//                $money = 1;
                // $money = floor($order->total_price * $proportion); // 舍去法取整
            }
            
            $receivers[0]['type'] = 'PERSONAL_OPENID'; //分账接收方类型： MERCHANT_ID：商户号（mch_id或者sub_mch_id）     PERSONAL_OPENID：个人openid
            $receivers[0]['account'] = $openid;
            $receivers[0]['amount'] = $money;
            $receivers[0]['description'] = '您邀请的用户，购买了本公司产品，推荐奖励已到账，已存入零钱。';
//        }

        $newPara["receivers"] = json_encode($receivers);
        // 签名

        $newPara["sign"] = $this->make_sign($newPara, $this->mch_secrect);
        //把数组转化成xml格式
        $xmlData = $this->array_to_xml($newPara);

        $options = array(
            CURLOPT_SSLCERT => config('wechat.app_cert_pem'),// 客户端证书，用于双向认证
            CURLOPT_SSLCERTTYPE => 'PEM',// 证书的类型。支持的格式有"PEM" (默认值), "DER"和"ENG"。
            CURLOPT_SSLKEY => config('wechat.app_key_pem'),// 客户端私钥的文件路径
            CURLOPT_SSLKEYTYPE => 'PEM',// 客户端私钥类型，支持的私钥类型为"PEM"(默认值)、"DER"和"ENG"。
            CURLOPT_KEYPASSWD => $this->mch_id,//客户端私钥密码，私钥在创建时可以选择加密。
            //API证书调用或安装需要使用到密码，该密码的值为微信商户号（mch_id）
        );

        $url = "https://api.mch.weixin.qq.com/secapi/pay/profitsharing";
        $get_data = $this->sendPrePayCurl($xmlData, $url, $options);

        return $get_data;
    }

    //发送请求
    public function sendPrePayCurl($xml, $url = '', $options = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);

        $data_xml_arr = $this->XMLDataParse($data);
        if ($data_xml_arr) {
            return $data_xml_arr;
        } else {
            return false;
        }
    }
    //xml格式数据解析函数
    public function XMLDataParse($data)
    {
        $xml = simplexml_load_string($data, NULL, LIBXML_NOCDATA);
        $array = json_decode(json_encode($xml), true);
        return $array;
    }


}