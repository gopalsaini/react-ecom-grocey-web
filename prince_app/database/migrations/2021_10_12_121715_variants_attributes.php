<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VariantsAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_attributes',function(Blueprint $table){
		   $table->id();
		   $table->foreignId('variant_id')->constrained('variants', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
		   $table->string('title');
		   $table->string('color');
		   $table->enum('status',['0','1'])->comment('0=>Pending,1=>Active')->default(1);
		   $table->integer('sort_order')->default('0');
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
