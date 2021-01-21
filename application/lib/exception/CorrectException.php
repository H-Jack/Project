<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/10
 * Time: 13:54
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


class CorrectException extends BaseException
{
    public $code = 501;

    public $msg = 'id不存在或不正确';

    public $error = 50001;
}