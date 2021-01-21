<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/10
 * Time: 12:38
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


class TeacherSyno extends Base
{
    protected $table = 'wechatsamll_teachersyno';

    /**
     * 关联CourseColumn课程表
     */
    public function Teachercomments()
    {
        return $this->hasMany('CourseColumn','teatherid','id');
    }

    /**
     * 关联本表
     */
    public function Associatedselftable()
    {
        return $this->hasOne('CourseColumn','teatherid','id'); //模型，外键，主键
    }

    /**
     * 老师搜索功能
     */
    public static function tearmodelsearch($str)
    {
        $list = self::where('teachername','like','%'.$str.'%')
            ->select();
        if(!$list)  //搜索结果为空，返回空数组
            return [];
        return $list;
    }
    /**
     * 获取所有老师数据
     * @return TeacherSyno[]
     */
    public static function selectlist()
    {
        $list = self::select();
        return $list;
    }

    /**
     * 通过前台传过来的id获取个老师数据
     * @param $id
     */
    public static function personalist($id)
    {
        $list = self::find($id);
        return $list;
    }

    /**
     * 通过id获取二维码链接 ，返回给我的预约模块
     * @param $id
     * @return mixed
     */
    public static function Getqrcode($id)
    {
        $list = self::where('id',$id)->value('qrcode');
        return $list;
    }

}