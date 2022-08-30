<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products',function(Blueprint $table){
			$table->id();
			$table->foreignId('category_id')->constrained('categories', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->string('variant_id');
			$table->string('name')->unique();
			$table->decimal('tax_ratio',18,2)->decimal(18,2);
			$table->text('short_description');
			$table->text('description');
			$table->enum('top_selling',['0','1'])->comment('0=>No,1=>Yes')->default(0);
			$table->enum('deals_oftheday',['0','1'])->comment('0=>No,1=>Yes')->default(0);
			$table->enum('deals_oftheweek',['0','1'])->comment('0=>No,1=>Yes')->default(0);
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
