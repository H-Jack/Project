<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/17
 * Time: 21:04
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


namespace app\api\model;


class CourseDetails extends Base
{
    protected $table = 'wechatsamll_coursedetails';
    protected $hidden = ['create_time','uptate_time','visitauth','orders'];
}