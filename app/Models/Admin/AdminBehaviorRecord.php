<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AdminBehaviorRecord extends Model
{

    protected $table = 'admin_behavior_record';

    protected $fillable = ['behavior' , 'target' , 'client_ip' , 'handle'];


    public function structure()
    {
        return Schema::create('admin_behavior_record', function ($table) {

            $table->bigIncrements('id');
            $table->string('behavior' , 32);
            $table->string('target' , 32);
            $table->string('client_ip' , 15);
            $table->integer('handle');
            $table->timestamps();
        });
    }
}
