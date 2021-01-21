<?php

namespace app\http\middleware;

use app\lib\exception\CatogenException;
use app\lib\exception\TokenisException;

class Check
{
    public function handle($request, \Closure $next)
    {
        $refresh_token = $request->header('token2');
        $access_token = $request->header('token');
        if($request->path() == 'api/v1/login')  //如果是登录接口那么不验证token
            return $next($request);
        if($request->path() == 'wechat/api/notify')
            return $next($request);
        if(empty($refresh_token) || empty($access_token))
            throw new CatogenException();
        return $next($request);
    }
}
