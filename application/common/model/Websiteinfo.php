<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/6/3
 * Time: 16:27
 */

namespace app\common\model;


use think\Model;

class Websiteinfo extends Model
{

    /**
     * 获取站点设置
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getWebsiteInfo()
    {
        return db('config')->where('config_id', 'in', [15, 16, 17, 18, 19, 20])->select();
    }
}