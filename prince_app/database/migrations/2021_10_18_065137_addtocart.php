<?php

use Illuminate\ Database\ Migrations\Migration;
use Illuminate\ Database\ Schema\ Blueprint;
use Illuminate\ Support\ Facades\ Schema;

class Addtocart extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('addtocarts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')-> onDelete('CASCADE')-> onUpdate('CASCADE');
            $table->foreignId('product_id')->constrained('variantproducts', 'id')->onDelete('CASCADE')-> onUpdate('CASCADE');
            $table->integer('qty')->default (0);
            $table->text('remark')->nullable();
            $table->timestamp('created_at')->default (DB::raw('CURRENT_TIMESTAMP'));
            $table-> timestamp('updated_at')->default (DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down() {
        //
    }
}
