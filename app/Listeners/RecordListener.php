<?php

namespace App\Listeners;

use App\Events\RecordEvent;
use App\Models\Admin\AdminBehaviorRecord;
use App\Services\AdminService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordListener
{
    /**
     * @var AdminBehaviorRecord $model
     */
    protected $model;

    /**
     * RecordListener constructor.
     * @param AdminBehaviorRecord $record
     */
    public function __construct(AdminBehaviorRecord $record)
    {
        $this->model = $record;
    }

    /**
     * @param RecordEvent $event
     * @return mixed
     * @throws \Exception
     */
    public function handle(RecordEvent $event)
    {
        if (method_exists($this, $event->method)) {
            return call_user_func_array([$this, $event->method], $event->params);
        }

        throw new \Exception('method not found : ' . __CLASS__ . '::' . $event->method);
    }

    //创建管理员成功
    protected function adminCreateSuccess($adminId)
    {
        $this->create(__FUNCTION__ , 'admin_id=' . $adminId);
    }

    //创建管理员失败
    protected function adminCreateFailed()
    {
        $this->create(__FUNCTION__ , 'account');
    }

    //修改管理员基本资料成功
    protected function adminModifySuccess($adminId)
    {
        $this->create(__FUNCTION__ , 'admin_id=' . $adminId);
    }

    //修改管理员基本资料失败
    protected function adminModifyFailed($adminId)
    {
        $this->create(__FUNCTION__ , 'admin_id=' . $adminId);
    }

    protected function createMenu($menuId)
    {
        $this->create(__FUNCTION__ , 'menu_id=' . $menuId);
    }

    protected function createGroup($groupId)
    {
        $this->create(__FUNCTION__ , 'group_id=' . $groupId);
    }

    protected function create($behavior, $target = '')
    {
        return $this->model->create([
            'behavior'  =>  $behavior,
            'target'    =>  $target,
            'handle'    =>  AdminService::getAdminId() ? AdminService::getAdminId() : 0,
            'client_ip' =>  request()->ip()
        ]);
    }
}
