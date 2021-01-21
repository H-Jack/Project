<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/9
 * Time: 15:47
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
use EasyWeChat\Factory;

class EasyWeChats
{
    protected static function index()
    {
        $config = [
            'app_id' => 'wxf8523261e19c2ed2',
            'secret' => 'f3644d93d13e31aab71a45a4557d3b65',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'wechat.log',
            ],
        ];
        $app = Factory::miniProgram($config);
        return $app;
    }

    /**
     * 微信登录获取openid和key
     * @param $code
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public static function login_wechat($code)
    {
        $app = self::index();
        $list = $app->auth->session($code);
        return $list;
    }
    /**
     * 微信消息解密电话等功能
     */
    public static function encry_wechat($session, $iv, $encryptedData)
    {
        $app = self::index();
        $decryptedData = $app->encryptor->decryptData($session, $iv, $encryptedData);
        return $decryptedData;
    }
}