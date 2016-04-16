<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 13:47
 */

namespace App\Services;


use App\Events\RecordEvent;
use App\Models\Admin\AdminGroup;
use App\Models\Admin\AdminGroupAccess;
use App\Models\Admin\AdminMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;

/**
 * Class AdminService
 * @package App\Services
 *
 * @property AdminMember admin
 * @property AdminGroup group
 * @property AdminGroupAccess groupAccess
 *
 * @method AdminMember|null create(array $form)
 */
class AdminService extends ServiceSupport
{

    private static $loginField = 'admin';

    protected $providers = [
        'admin' => AdminMember::class,
        'group' => AdminGroup::class,
        'groupAccess' => AdminGroupAccess::class,

    ];

    /**
     * 创建一个新的管理员
     * @param array $form
     * @return AdminMember|null
     */
    protected function _create(array $form)
    {
        try {

            $random = rand(100000, 999999);

            $form['random'] = $random;
            $form['password'] = $this->encryption($form['password'], $random);

            return $this->admin->create($form);
        } catch (\Exception $e) {

            $this->error->add('username', 'field value exception');
            return null;
        }
    }

    /**
     *  $this->_create 的后置方法
     * @param $result
     * @return mixed
     */
    protected function _createAfter($result)
    {
        event(new RecordEvent(is_null($result) ? 'createFailed' : 'createSuccess'));

        return $result;
    }

    /**
     * 验证登录账户是否正确
     * @param string $username 用户名
     * @param string $password 密码
     * @return int 成功：admin_id , 失败：0
     */
    public function verifyLogin($username, $password)
    {
        try {
            $admin = $this->admin->where(['username' => $username])->first();

            if ($admin->password == $this->encryption($password, $admin->random)) {
                return $admin->admin_id;
            }

            throw new \Exception;
        } catch (\Exception $e) {

            $this->error->add("username", '账户不存在或者密码不正确');
            return 0;
        }
    }

    public function autoLogin($adminId)
    {
        $admin = $this->admin->find($adminId);

        $groupAccess = $this->groupAccess->where(['admin_id' => 1])->select('group_id')->get();

        $groupWhere = [];

        foreach ($groupAccess as $value) {
            $groupWhere[] = $value->group_id;
        }

        $rules = [];

        $groups = $this->group->where('group_id', 'in', $groupWhere)->get();

        foreach ($groups as $group) {
            $rules = array_merge($rules, explode(',', $group->rules));
        }

        Session::put($this->loginField, [
            'rules' => $rules,
            'adminId' => $admin->admin_id,
            'username' => $admin->username,
            'loginTime' => time(),
        ]);
    }

    public static function checkLogin()
    {

    }

    public function encryption($password, $random)
    {
        return md5(md5($password) . $random);
    }
}