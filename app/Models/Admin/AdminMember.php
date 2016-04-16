<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminMember extends Model
{

    protected $table = 'admin_member';

    protected $primaryKey = 'admin_id';

    protected $fillable = ['username', 'password', 'random' , 'nickname' , 'mobile' , 'sex'];

    //结构
    public function structure()
    {
        return Schema::create('admin_member', function($table)
        {
            $table->increments('admin_id');
            $table->string('username' , 16)->unique();
            $table->string('password' , 32);
            $table->integer('random');
            $table->string('nickname' , 8);
            $table->string('mobile' , 11);
            $table->enum('sex' , ['男'  , '女' , '其他'])->default('其他');
            $table->timestamps();

            // Y:正常，N:禁用 ，D:伪删除
            $table->enum("status" , ['Y' , 'N' , 'D']);
        });
    }
}
