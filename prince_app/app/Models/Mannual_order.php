<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mannual_order extends Model
{
    use HasFactory;

    public function getsalesChildDetail(){

        return $this->hasMany('App\Models\Mannualorder_detail','parent_id','id');
    }
}
