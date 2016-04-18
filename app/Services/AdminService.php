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
use Illuminate\Support\Facades\Session;

/**
 * Class AdminService
 * @package App\Services
 *
 * @property AdminMember admin
 *
 * @method AdminMember|null create(array $form)
 * @method bool modify(array $form)
 */
class AdminService extends ServiceSupport
{

    protected $providers = [
        'admin' => AdminMember::class,
    ];

    protected static $loginField = 'admin';

    //当前登录的管理员登录信息
    protected static $currentAdmin = [];


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

            $this->error->add('username', '用户名已经存在，换一个呗~！');
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
        if(is_null($result)) {
            event(new RecordEvent('adminCreateFailed'));
        } else {
            event(new RecordEvent('adminCreateSuccess') , ['adminId' => $result->admin_id]);
        }
        return $result;
    }

    /**
     * 修改管理员基本资料
     * @param array $form
     * @param int $adminId
     * @return bool
     */
    protected function _modify(array $form, $adminId = 0)
    {
        $adminId <= 0 && $adminId = static::getAdminId();

        try {

            $model = $this->admin->find($adminId);

            $model->sex = $form['sex'];
            $model->mobile = $form['mobile'];
            $model->nickname = $form['nickname'];

            $result = $model->save();

            event(new RecordEvent('adminModifySuccess', ['adminId' => $adminId]));

            return $result;
        } catch (\Exception $e) {

            $this->errors->add('error', 'field value exception');
            event(new RecordEvent('adminModifyFailed', ['adminId' => $adminId]));
            return false;
        }
    }

    /**
     * 重置管理员密码
     * @param $password
     * @param int $adminId
     * @return mixed
     */
    protected function repeatPassword($password , $adminId = 0)
    {
        $adminId <= 0 && $adminId = static::getAdminId();

        $admin = $this->admin->find($adminId);

        $admin->password = $this->encryption($password , $admin->random);

        return $admin->save();
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

            $this->errors->add("username", '账户不存在或者密码不正确');
            return 0;
        }
    }

    /**
     * 用户快捷登录
     * @param int $adminId
     * @param array $rules
     */
    public function autoLogin($adminId, array $rules)
    {
        $admin = $this->admin->find($adminId);

        return session()->put([static::$loginField => [
            'rules' => $rules,
            'adminId' => $admin->admin_id,
            'username' => $admin->username,
            'loginTime' => time(),
        ]]);
    }

    /**
     * 判断管理员是否登录
     * @return bool
     */
    public static function checkLogin()
    {
        return static::getAdminLoginInfo() ? true : false;
    }

    /**
     * 获取当前登录管理员的admin_id
     * @return mixed
     */
    public static function getAdminId()
    {
        return static::getAdminLoginInfo('adminId');
    }

    /**
     * 获取当前管理员的登录信息，
     * @see AdminService::autoLogin
     * @param $field
     * @return mixed
     */
    public static function getAdminLoginInfo($field = '')
    {
        if (empty(static::$currentAdmin)) {
            static::$currentAdmin = Session::get(static::$loginField);
        }

        return empty($field) ? static::$currentAdmin : static::$currentAdmin[$field];
    }

    /**
     * 用户密码加密算法
     * @param $password
     * @param $random
     * @return mixed
     */
    public function encryption($password, $random)
    {
        return md5(md5($password) . $random);
    }


}