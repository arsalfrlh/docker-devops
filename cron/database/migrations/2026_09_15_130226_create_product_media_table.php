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
        Schema::create('product_media', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('product_id');
            $table->enum('type',['image','video']);
            $table->string('media_path');
            $table->integer('duration')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
