<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sliders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders',function(Blueprint $table){
			$table->id();
			$table->string('image');
			$table->string('link');
			$table->integer('sort_order')->default(0);
			$table->enum('status',['0','1'])->comment('0=>Pending,1=>Active')->default(0);
			$table->enum('recyclebin_status',['0','1'])->comment('0=>No,1=>Yes')->default(0);
			$table->dateTime('recyclebin_datetime')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
