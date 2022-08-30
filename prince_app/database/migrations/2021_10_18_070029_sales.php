<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales',function(Blueprint $table){
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained('users', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->enum('checkout_type',['1','2'])->comment('1=>Guest,2=>Customer');
			$table->string('order_id')->unique();
			$table->integer('total_created_order')->default('0');
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('phone_code')->nullable();
			$table->string('mobile')->nullable();
			$table->integer('currency_id')->default('0');
			$table->integer('country_id')->default(0);
			$table->integer('state_id')->default(0);
			$table->integer('city_id')->default(0);
			$table->text('address_line1');
			$table->text('address_line2')->nullable();
			$table->string('pincode');
			$table->enum('type',['1','2'])->comment('1=>Home,2=>Office');
			$table->enum('payment_type',['1','2'])->comment('1=>Online payment,2=>Cod');
			$table->decimal('subtotal',18,2)->default('0.00');
			$table->decimal('discount',18,2)->default('0.00');
			$table->decimal('shipping',18,2)->default('0.00');
			$table->integer('couponcode_id')->default('0');
			$table->decimal('couponcode_amount',18,2)->default('0.00');
			$table->decimal('net_amount',18,2)->default('0.00');
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
