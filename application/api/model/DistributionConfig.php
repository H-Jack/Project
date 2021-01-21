<?php


namespace app\api\model;


class DistributionConfig extends Base
{
    protected $table = 'wechat_distribution_config';

    public function findCourseRate()
    {
        return $this->where('type','rate')->value("course_val");
    }

    public function findAdvisoryRate()
    {
        return $this->where('type','rate')->value("advisory_val");
    }
}