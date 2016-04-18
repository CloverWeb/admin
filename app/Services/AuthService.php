<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/18
 * Time: 0:37
 */

namespace App\Services;


use App\Models\Admin\AdminGroup;
use App\Models\Admin\AdminGroupAccess;
use App\Models\Admin\AdminMenu;

/**
 * Class AuthService
 * @package App\Services
 *
 * @property AdminMenu menu
 * @property AdminGroup group
 * @property AdminGroupAccess groupAccess
 *
 * @method AdminGroup createGroup(array $group)
 * @method AdminMenu createMenu(array $menu)
 * @method AdminGroupAccess dispatchAdminToGroup($adminId, $groupId)
 */
class AuthService extends ServiceSupport
{

    protected $providers = [
        'menu' => AdminMenu::class,
        'group' => AdminGroup::class,
        'groupAccess' => AdminGroupAccess::class,
    ];

    public function checkAuth($adminId, $uri)
    {
        $this->findRules($adminId);
    }

    /**
     * 获取指定用户所拥有的权限
     * @param $adminId
     * @return array
     */
    public function findRules($adminId)
    {
        $groupAccess = $this->groupAccess->where(['admin_id' => $adminId])->get();

        $groups = [];

        foreach ($groupAccess as $value) {
            $groups[] = $value->group_id;
        }

        $groups = $this->group->where('group_id', 'in', $groups)->select('rules')->get();

        $rules = [];

        foreach ($groups as $group) {
            $rules = array_merge($rules, $group->rules);
        }

        return $rules;
    }

    public function findGroups()
    {
        return null;
    }

    /**
     * 从权限组移除管理员
     * @param $adminId
     * @param $groupId
     */
    protected function _removeGroupToAdmin($adminId, $groupId)
    {

    }

    /**
     * 移除菜单
     * @param $menuId
     */
    protected function _removeMenu($menuId)
    {

    }

    /**
     * 移除权限组，并且移除与之关联的 group_access
     * @param $groupId
     */
    protected function _removeGroup($groupId)
    {

    }

    /**
     * 分配一个用户到权限组中
     * @param int $adminId
     * @param int $groupId
     * @return static
     */
    protected function _dispatchAdminToGroup($adminId, $groupId)
    {
        return $this->groupAccess->create([
            'admin_id' => $adminId,
            'group_id' => $groupId
        ]);
    }

    /**
     * $this->_dispatchAdminToGroup() 的后置方法
     * @see AuthService::_dispatchAdminToGroup()
     * @param $result
     * @return mixed
     */
    protected function _dispatchAdminToGroupAfter($result)
    {
        $this->behaviorRecord('adminToGroup', [
            'adminId' => $result->admin_id,
            'groupId' => $result->group_id
        ]);

        return $result;
    }

    /**
     * 创建一个新的菜单
     * @param array $menu
     * @return AdminMenu
     */
    protected function _createMenu(array $menu)
    {
        try {
            return $this->menu->create($menu);
        } catch (\Exception $e) {
            $this->errors->add('menu_name', '菜单名称已经存在了，换一个咯！');
            return null;
        }
    }

    /**
     * $this->_createMenu() 后置方法
     * @see AuthService::_createMenu
     * @param $result
     * @return mixed
     */
    protected function _createMenuAfter($result)
    {
        null != $result && $this->behaviorRecord('createMenu', ['menuId' => $result->menu_id]);

        return $result;
    }

    /**
     * 创建一个新的权限组
     * @param array $group
     * @return AdminGroup
     */
    protected function _createGroup(array $group)
    {
        try {
            return $this->group->create($group);
        } catch (\Exception $e) {
            $this->errors->add('group_name' , '权限组名称已经存在，换一个咯！');
            return null;
        }
    }

    /**
     * $this->_createGroup() 后置
     * @see AuthService::_createGroup()
     * @param $result
     * @return mixed
     */
    protected function _createGroupAfter($result)
    {
        null != $result && $this->behaviorRecord('createGroup' , ['groupId' => $result->group_id]);

        return $result;
    }

}