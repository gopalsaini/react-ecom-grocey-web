<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    public function getsalesdetailchild(){
	
        return $this->hasMany('App\Models\Sales_detail','sale_id','id');
    }


}
