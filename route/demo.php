<?php


use think\facade\Request;
use think\facade\Route;

//Route::any('api/v1/login','api/v1.login/login'); // 所有请求都支持的路由规则

Route::group('api/v1',[
    'messageboardindex' => 'api/v1.MessageBoard/index',
    'messageboardsave' => 'api/v1.MessageBoard/save',
    'wechatblogindex' => 'api/v1.WechatBlog/index',
    'wechatblogsave' => 'api/v1.WechatBlog/save',
    'login'        => 'api/v1.login/login',
    'myindex'        => 'api/v1.my/index',
    'myqiandao'        => 'api/v1.my/qiandao',
    'qdstatus'        => 'api/v1.my/qdstatus',
    'mysave'            => 'api/v1.my/save',
    'coursecolumn' => 'api/v1.course_column/putheacherlist',
    'returnthespecifiedcourse' => 'api/v1.course_column/Returnthespecifiedcourse',
    'Backtochapterlink'  => 'api/v1.course_details/Backtochapterlink',
    'WechatPaymentpay' => 'api/v1.wechat_payment/pay',
    'ordertimeindex'         => 'api/v1.order_time/index',
    'allorderlist'  => 'api/v1.wechat_order/putorderlist',
    'returntoallcoursesandteachers' => 'api/v1.public_requestfunction/Returntoallcoursesandteachers',
    'judgewhethertoupdate'  => 'api/v1.public_requestfunction/Judgewhethertoupdate',
    'integralcourseall'     =>  'api/v1.course_column/Integralcourseall',
    'paypointsexchange'     =>  'api/v1.wechat_payment/PayPointsexchange',
    'coursearchs'       =>  'api/v1.course_column/coursearch',
    'tearchsearchs'     =>  'api/v1.teacher_syno/tearchsearch',
]);

Route::any('wechat/api/notify','api/wechat_unify/notify');