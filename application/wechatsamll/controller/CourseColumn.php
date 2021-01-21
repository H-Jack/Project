<?php

/**
 * Created by PhpStorm.
 * User: benjieming421
 * Date: 2020/11/26
 * Time: 10:50
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

namespace app\wechatsamll\controller;


use library\Controller;
use think\Db;
use think\Request;
use app\wechatsamll\model\Coursedetails as editset;
use app\wechatsamll\model\Coursecontent as editcont;
use app\wechatsamll\model\Coursecolumn as column;
use app\wechatsamll\model\Teachersyno;
/**
 * 课程栏目管理
 * Class CourseColumn
 * @package app\wechatsamll\controller
 */
class CourseColumn extends Controller
{
    /**
     * 绑定当前数据表
     * @var string
     */
    protected $table = 'WechatsamllCoursecolumn';   //课程列表表
    protected $table2 = 'WechatsamllCoursedetails'; //课程详细表
    protected $table3 = 'WechatsamllCoursecontent';  //课程产品内容表

    /**
     * 表单结果处理
     * @param boolean $result
     */
    protected function _form_result($result)
    {
        if($this->title == '添加课程')
        {
            $editset = new editset;
            $getchapnum = intval(column::get($result)->chaptersnumber);//获取章节数量
            $list = [];
            if($getchapnum > 30)   //判断章节数量不能大于30
            {
                $this->error('不能大于30章节,请重新删除添加的课程。重新操作！！！');
            }
            for ($i=0;$i<$getchapnum;$i++)
            {
                array_push($list,['classid' => $result,'orders'=>$i]);
            }
            $editset->isUpdate(false)->saveAll($list);

            $editcont = new editcont();
            $editcont->save([
                'classid' => $result
            ]);
        }

    }

    /**
     * 进入课程管理首页
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '课程栏目管理';
        $query = $this->_query($this->table)->like('');
        $query->dateBetween('create_at')->order('id desc')->page();
    }

    /**
     * @auth true
     * 删除课程
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }
    /**
     * @auth true
     * 添加课程
     */
    public function add()
    {
        $this->title = '添加课程';
        $teacherlist = $this->teacherlist();
        $this->_form($this->table, 'form','',[],['teacherlist'=>$teacherlist]);
    }

    /**
     * @auth true
     * 编辑课程
     */
    public function edit()
    {
        $this->title = '编辑课程';
        $this->isAddMode = '0';



        $editset = new editset;
        $column = new column();
        $editcont = new editcont();
        $result = input('post.');
        $classid = input('get.id/d');
        $detail = $editcont->where(['classid' => $classid])->find();
        $getteachername = Teachersyno::hasWhere('bindcommon',['id'=>$classid])->find(); //关联本课程获取老师名字
        if(!empty($result))   //插入课程详细内容表
        {
            $editcont->editselect($result['datail'],$classid);
            $column->editsave($classid,$result);
            $editset->editsavelike($result['videolike'],$result['videoname'],$classid);  //保存 把章节链接插入数据库
        }
        $detaillist = $this->generatelist($classid); //获取所有章节
        $this->_form($this->table, 'edit','',[],['datail'=>$detail,'detaillist'=>$detaillist,'teachername'=>$getteachername->teachername]);
//
//        $coldata = $column->where('id',$classid)->find();
//        $this->_form($this->table2, 'edit','classid',['classid'=>$classid],['datail'=>$detail,'price'=>$coldata->price,'title'=>$coldata->coursename]);
    }
    /**
     * 获取所有老师
     */
    protected function teacherlist()
    {
        $teachersyno = new Teachersyno();
        return $teachersyno->field('teachername,id')->select();
    }

    /**
     * 获取所有章节
     */
    protected function generatelist($classid)
    {
        $editset = new editset();
        $list = $editset->where(['classid' => $classid])->order('orders','asc')->select();
        $result = [];
        if(empty($list))
        {
            return ;
        }
        foreach ($list as $v)
        {
            array_push($result,['like'=>$v->videolike,'name'=>$v->videoname]);
        }
        return $result;
    }

}