<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/12/19
 * Time: 19:49
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


namespace app\api\controller\v1;


use app\api\controller\Base;
use app\api\validate\TeacherSynolist;
use app\lib\exception\CorrectException;
use app\api\model\CourseDetails as detail;
use app\api\model\WechatBlogModel;
use think\Facade;
use app\api\controller\v1\CourseColumn;
use app\api\model\CourseColumn as column;

class CourseDetails extends Base
{
    /**
     * 返回获取id的课程章节
     * @param $id
     */
    public function Backtochapterlink()
    {
        $validate = new TeacherSynolist;
        $WechatBlogModel = new WechatBlogModel;
        $CourseColumn = new CourseColumn;
        $id = input('get.id');//获取章节id
        $cid = json_decode(input('get.data'));
        if(empty($id) || empty($cid))
            return false;
        //判断订单是否购买成功
        $ifbuy = $CourseColumn->Judgewhetherusersbuycourses($cid[0]);
        //判断价钱是否免费
        $price = $this->Freecourses($cid[0]);
        if(!$ifbuy && !$price)
            return false;
        $idarr = ['id' => $id];
        $list = [];
        if (!$validate->scene('searchteacher')->check($idarr))
            throw new CorrectException();
        $data = detail::get($id);
        if (!empty($data)) {
            $data = detail::get($id)->toArray();
            $list = $WechatBlogModel->index($data['classid'])->toArray();
        }
        return [$data,$list];
    }
    /**
     * 判断课程是否免费
     */
    public function Freecourses($cid)
    {
        $coursecolumn = column::personalist($cid);
        $price = $coursecolumn->price;
        //如果为0.00就返回true
        if($price == '0.00')
            return true;
        return false;
    }
}