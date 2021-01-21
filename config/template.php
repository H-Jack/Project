<?php



use think\facade\Config;
use think\facade\Request;

return [
    // 去除HTML空格换行
    'strip_space'        => true,
    // 开启模板编译缓存
    'tpl_cache'          => !Config::get('app_debug'),
    // 定义模板替换字符串
    'tpl_replace_string' => [
        '__APP__'  => rtrim(url('@'), '\\/'),
        '__ROOT__' => rtrim(dirname(Request::basefile()), '\\/'),
        '__FULL__' => rtrim(dirname(Request::basefile(true)), '\\/'),
    ],
];
