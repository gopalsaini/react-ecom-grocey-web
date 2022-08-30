<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOndemandEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ondemand_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('mobile',100)->nullable();
            $table->string('email',100)->nullable();
            $table->foreignId('product_id')->constrained('variantproducts', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('qty');
            $table->longtext('description');
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
        Schema::dropIfExists('ondemand_enquiries');
    }
}
