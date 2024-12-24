<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_favorite_folder_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('other_favorite_id');
            $table->unsignedBigInteger('other_folder_id');
            $table->timestamps();

            $table->foreign('other_favorite_id')->references('id')->on('other_favorites')->onDelete('cascade');
            $table->foreign('other_folder_id')->references('id')->on('other_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_favorite_folder_relationships');
    }
};
