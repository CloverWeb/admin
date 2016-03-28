<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 16:19
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminMember extends Model
{

    protected $table = 'admin_member';

    protected $fillable = ['username', 'password', 'random', 'status'];

    protected $hidden = ['remember_token'];

    /**
     * 表结构
     */
    private function structure()
    {
        Schema::create('admin_member', function ($table) {

            $table->increments('admin_id');
            $table->string('username', 16)->unique();
            $table->string('password', 32);
            $table->integer('random');
            $table->enum('sex', ['男', '女', '其他']);
            $table->boolean('status');
            $table->rememberToken();
            $table->timestamps();

        });
    }
}