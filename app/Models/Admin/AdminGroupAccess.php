<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 17:57
 */

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminGroupAccess extends Model
{
    public function structure()
    {
        return Schema::create('admin_group_access', function ($table) {

            $table->increments('id');
            $table->integer('group_id')->unsigned();          //组名称
            $table->integer('menu_id')->unsigned();              //备注，解释
        });
    }
}