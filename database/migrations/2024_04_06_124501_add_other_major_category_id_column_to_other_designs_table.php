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
        // Add other_major_category_id column
        Schema::table('other_designs', function (Blueprint $table) {
            $table->unsignedBigInteger('other_major_category_id')->nullable();
            $table->foreign('other_major_category_id')->references('id')->on('other_major_categories')->onDelete('SET NULL');
        });

        // Drop other_category_id column
        Schema::table('other_designs', function (Blueprint $table) {
            $table->dropColumn('other_category_id');
        });
    }

    public function down()
    {
        // Add other_category_id column
        Schema::table('other_designs', function (Blueprint $table) {
            $table->unsignedBigInteger('other_category_id')->nullable();
            $table->foreign('other_category_id')->references('id')->on('other_categories')->onDelete('SET NULL');
        });

        // Drop other_major_category_id column
        Schema::table('other_designs', function (Blueprint $table) {
            $table->dropForeign(['other_major_category_id']);
            $table->dropColumn('other_major_category_id');
        });
    }
};
