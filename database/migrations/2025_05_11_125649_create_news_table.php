<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('language');
            $table->string('type');
            $table->longText('title')->nullable();
            $table->boolean('show_in_slider')->default(false);
            $table->integer('order_slider')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->integer('order_featured')->default(0);
            $table->string('editor_id')->nullable();
            $table->string('source')->nullable();
            $table->string('short_url')->nullable();
            $table->string('url_short_key')->nullable();
            $table->string('main_image')->nullable();
            $table->string('publisher_id')->nullable();
            $table->longText('sub_title')->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->text('image_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->date('schudle_date')->nullable();
            $table->time('schudle_time')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('category_id');
            $table->integer('sub_category_id')->nullable();


            //Special editions
           $table->string('file')->nullable();
           $table->string('direction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
