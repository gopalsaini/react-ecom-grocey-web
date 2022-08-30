<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Currencyvalues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        Schema::create('currency_values',function(Blueprint $table){
            $table->id();
            $table->string('name')->unique();
            $table->string('currency_code')->unique();
            $table->string('image');
            $table->string('first_icon');
            $table->string('second_icon')->nullable();
            $table->string('value')->comment('value  of 1 rs');
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
