<?php
/**
 * Author: miaomin
 *
 * 2018-03-28
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use think\Cache;

class RedisController extends AdminBaseController
{

    public function _initialize()
    {
        $adminSettings = cmf_get_option('admin_settings');
        if (empty($adminSettings['admin_password']) || $this->request->path() == $adminSettings['admin_password']) {
            $adminId = cmf_get_current_admin_id();
            if (empty($adminId)) {
                session("__LOGIN_BY_CMF_ADMIN_PW__", 1);//设置后台登录加密码
            }
        }

        parent::_initialize();
    }

    /**
     * Redis首页
     */
    public function index()
    {

        $targetArr = array(
            '2018010101',
            '2018010102',
            '2018010104'
        );

        $filterArr = array(
            '2018010103'
        );

        $options = ['type' => 'redis', 'password' => '', 'host' => '127.0.0.1'];

        $redis = new \Redis;
        $redis->connect($options['host']);
        $redis->sAdd('targetDate','2018010101');
        $redis->sAdd('targetDate','2018010102');
        $redis->sAdd('targetDate','2018010104');

        $redis->sAdd('filterDate','2018010103');
        $redis->sAdd('filterDate','2018010102');

        $res = $redis->sDiff('targetDate','filterDate');

        return $this->fetch();
    }
}
