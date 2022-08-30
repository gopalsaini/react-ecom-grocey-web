<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Categories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        
        Schema::create('categories',function(Blueprint $table){
			$table->id();
			$table->string('name',100);
			$table->string('slug',100)->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->string('image')->nullable();
			$table->longtext('description')->nullable();
			$table->enum('top_category',['0','1'])->comment('0=>No,1=>Yes')->default(0);
			$table->enum('status',['0','1'])->comment('0=>Pending,1=>Active')->default(0);
            $table->string('meta_title');
            $table->text('meta_keywords');
            $table->text('meta_description');
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
