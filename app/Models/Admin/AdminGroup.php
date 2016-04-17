<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 17:43
 */

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminGroup extends Model
{
    protected $table = 'admin_group';

    protected $fillable = ['group_name' , 'title' , 'rules'];

    public function structure()
    {
        return Schema::create('admin_group', function ($table) {

            $table->increments('group_id');
            $table->string('group_name' , 16);          //组名称
            $table->string('title' , 32);              //备注，解释
            $table->text('rules');
            $table->timestamps();
        });
    }

}