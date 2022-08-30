<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Coupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons',function(Blueprint $table){
			$table->id();
			$table->string('title');
			$table->string('coupon_code');
			$table->date('start_date');
			$table->date('end_date');
			$table->enum('discount_type',['1','2'])->default('1')->comment('1=>%,2=>Rs');
			$table->decimal('discount_amount',18,2)->default('0.00');
            $table->integer('totalno_uses')->default('0');
            $table->decimal('minorder_amount',18,2)->default('0');
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
