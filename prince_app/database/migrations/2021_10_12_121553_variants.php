<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Variants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('variants',function(Blueprint $table){
		   $table->id();
		   $table->string('name');
		   $table->integer('sort_order')->default(0);
		   $table->enum('display_layout',['1','2','3'])->comment('1=>Dropdown swatch,2=>visual swatch,3=>Text swatch');
		   $table->enum('status',['0','1'])->comment('0=>Pending,1=>Active')->default(1);
		   $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
           $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));		   
	   });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
