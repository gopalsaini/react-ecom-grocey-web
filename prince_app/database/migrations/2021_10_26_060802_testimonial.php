<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Testimonial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonials',function(Blueprint $table){
			$table->id();
			$table->string('name',100);
			$table->string('designation',100);
			$table->string('image',100);
			$table->text('description');
			$table->enum('status',['0','1'])->comment('0=>Pending,1=>Active')->default(0);
			$table->enum('recyclebin_status',['0','1'])->comment('0=>No,1=>Yes')->default(0);
			$table->dateTime('recyclebin_datetime')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP'));
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
