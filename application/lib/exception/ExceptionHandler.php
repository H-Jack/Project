<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/9
 * Time: 21:30
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


namespace app\lib\exception;

use app\admin\behavior\Log;  //日志
use Exception;				//php异常捕获
use think\exception\Handle;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $error;
    public function render(Exception $e)
    {
        if($e instanceof BaseException)  //判断$e捕获到的是否为自定义异常
        {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error = $e->error;
        }else{
            if(config('app_debug')){
                return parent::render($e);
            }
            $this->code = 500;
            $this->msg = '服务器内部错误，开发人员太水了';
            $this->errorCode = 999;
            $this->recordErrorLog($e);
        }
        $result = [
            'msg' => $this->msg,
            'error' => $this->error
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(Exception $e)
    {
        Log::record($e->getMessage(), 'error');
    }
}