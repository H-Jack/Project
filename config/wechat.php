<?php



return [
    // 微信开放平台接口
    'service_url' => 'https://demo.thinkadmin.top',
    // 小程序支付参数
    'miniapp'     => [
        'appid'      => 'wx8c108930fe12b7ef',
        'appsecret'  => '13d829992a2b6a0a44195a4a580da56d',
        'mch_id'     => '1332187001',
        'mch_key'    => 'A82DC5BD1F3359081049C568D8502BC5',
        'ssl_p12'    => __DIR__ . '/cert/1332187001_20181030_cert.p12',
        'cache_path' => env('runtime_path') . 'wechat' . DIRECTORY_SEPARATOR,
    ],
    'key' => 'xiao',

    'app_id'             => 'wxf8523261e19c2ed2',
    'mch_id'             => '1604297902',
    'keys'                => 'rYiHsge3v5Y0rMI8fCL2h6TddlEhY0A7',   // API 密钥
    'notify_url'         => 'http://yxeih2.natappfree.cc/index.php/wechat/api/notify',     // 你也可以在下单时单独设置来想覆盖它
    'app_cert_pem'  => __DIR__ . '/cert/apiclient_cert.pem',
    'app_key_pem'  => __DIR__ . '/cert/apiclient_key.pem'

];
