<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 16:59
 */

namespace App\Services;

use App\Models\AdminMember;

/**
 * Class AdminService
 * @package App\Services
 * @property AdminMember $adminMember
 */
class AdminService extends SupportService
{

    protected $binds = [
        'adminMember' => 'App\Models\AdminMember',
    ];

    /**
     * @param array $admin
     * @return int
     */
    public function create(array $admin)
    {
        try {
            $adminMember = $this->adminMember;

            $random = rand(100000, 999999);

            $adminMember->sex = $admin['sex'];
            $adminMember->status = true;
            $adminMember->username = $admin['username'];
            $adminMember->password = $this->encryptionPassword($admin['password'], $random);
            $adminMember->random = $random;

            return $adminMember->save();
        } catch (\Exception $e) {
            return $this->setError('用户名已被占用');
        }
    }

    private function encryptionPassword($password, $random)
    {
        return md5(md5($password) . $random);
    }
}