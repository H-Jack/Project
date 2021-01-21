<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/14
 * Time: 16:31
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


class CourseColumn extends Base
{
    protected $table = 'wechatsamll_coursecolumn';
    protected $hidden = ['create_time','update_time'];

    /**
     * 关联CourseDetails章节表
     */
    public function Chaptercomments()
    {
        return $this->hasMany('CourseDetails','classid','id');
    }

    /**
     * 课程搜索功能
     */
    public static function courmodelsearch($str)
    {
        $list = self::where('coursename|coursetype','like','%'.$str.'%')
                ->select();
        if(!$list)  //搜索结果为空，返回空数组
            return [];
        return $list;
    }

    /**
     * 获取所有老师数据
     * @return TeacherSyno[]
     */
    public static function selectlists()
    {
        $list = self::select();
        return $list;
    }

    /**
     * 通过前台传过来的id获取个课程数据
     * @param $id
     */
    public static function personalist($id)
    {
        $list = self::find($id);
        return $list;
    }

    /**
     * 返回可积分兑换的课程，用于积分商城
     */
    public static function integralcourselist()
    {
        $list = self::where('exchangepoints',1)->select();
        return $list;
    }

}