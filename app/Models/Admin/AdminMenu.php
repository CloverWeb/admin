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

    protected $fillable = ['menu_name' , 'title' , 'url' , 'pid'];

    protected $primaryKey = 'menu_id';

    public function structure()
    {
        return Schema::create('admin_menu', function ($table) {

            $table->increments('menu_id');
            $table->string('menu_name' , 8)->unique();          //组名称
            $table->string('title' , 32);              //备注，解释
            $table->text('url');                        //菜单url
            $table->integer('pid')->unsigned();                     //上级ID
            $table->timestamps();
        });
    }
}