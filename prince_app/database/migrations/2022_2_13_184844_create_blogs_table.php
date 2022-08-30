<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->longtext('short_desc');
            $table->longtext('description');
            $table->string('image',100);
            $table->foreignId('category_id')->constrained('blog_categories', 'id')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('slug',200);
            $table->enum('status',['0','1'])->default('0')->comment('0=>Pending,1=>Active');  
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
        Schema::dropIfExists('blogs');
    }
}
