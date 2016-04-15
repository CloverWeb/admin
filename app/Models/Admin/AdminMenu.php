<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 17:51
 */

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';

    public function structure()
    {
        return Schema::create('admin_menu', function ($table) {

            $table->increments('menu_id');
            $table->string('menu_name' , 8);          //组名称
            $table->string('title' , 32);              //备注，解释
            $table->integer('rules');
            $table->timestamps();

            // Y:正常，N:禁用 ，D:伪删除
            $table->enum("status" , ['Y' , 'N' , 'D']);
        });
    }
}