<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mannualordersdetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mannualorder_details',function(Blueprint $table){
            $table->id();
            $table->foreignId('parent_id')->constrained('mannual_orders','id')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('order_id');
            $table->string('product_name');
            $table->decimal('price',18,2)->default('0.00');
            $table->integer('quantity')->default(0);
            $table->decimal('subtotal',18,2)->default('0.00');
            $table->decimal('discount_ratio',18,2)->default('0.00');
            $table->decimal('discount_amount',18,2)->default('0.00');
            $table->decimal('gst_ratio',18,2)->default('0.00');
            $table->decimal('gst_amount',18,2)->default('0.00');
            $table->decimal('net_total',18,2)->default('0.00');
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
