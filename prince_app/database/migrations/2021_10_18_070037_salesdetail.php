<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Salesdetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        
        Schema::create('sales_details',function(Blueprint $table){
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained('users', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->foreignId('sale_id')->constrained('sales', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('currency_id')->default('0');
			$table->string('order_id')->foreignId('order_id')->constrained('sales', 'order_id')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('suborder_id',100)->nullable();
			$table->foreignId('product_id')->constrained('variantproducts', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->string('product_name');
			$table->string('product_image');
			$table->string('qty');
			$table->text('remark')->nullable();
			$table->decimal('sub_total',18,2)->default('0.00');
			$table->decimal('discount',18,2)->default('0.00');
			$table->decimal('amount',18,2)->default('0.00');
			$table->integer('is_approve')->default(0)->comment('1=>Yes,2=>No');
			$table->integer('order_status')->default(0);
			$table->integer('payment_status')->default(0);
			$table->decimal('refund_amount',18,2)->default('0.00');
            $table->string('waybill_no')->nullable();
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
