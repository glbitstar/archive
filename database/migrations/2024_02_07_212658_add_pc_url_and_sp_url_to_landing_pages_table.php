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
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('pc_url')->nullable(); // PC版のURL
            $table->string('pc_image')->nullable();
            $table->string('sp_url')->nullable(); // SP版のURL
            $table->string('sp_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn('pc_url');
            $table->dropColumn('pc_image');
            $table->dropColumn('sp_url');
            $table->dropColumn('sp_image');
        });
    }
};
