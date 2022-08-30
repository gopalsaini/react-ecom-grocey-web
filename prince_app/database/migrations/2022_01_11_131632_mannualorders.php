<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mannualorders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mannual_orders',function(Blueprint $table){
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('currency_id')->constrained('currency_values','id')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('name');
            $table->string('phone_code');
            $table->string('mobile');
            $table->string('email');
            $table->text('address_line1');
            $table->text('address_line2');
            $table->integer('country_id')->default('0');
            $table->integer('state_id')->default('0');
            $table->integer('city_id')->default('0');
            $table->string('pincode');
            $table->decimal('subtotal',18,2)->default('0.00');
            $table->decimal('discount',18,2)->default('0.00');
            $table->decimal('gst',18,2)->default('0.00');
            $table->decimal('nettotal',18,2)->default('0.00');
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
