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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['representative_id']);
            $table->unsignedBigInteger('representative_id')->nullable()->change();
            $table->foreign('representative_id')->references('id')->on('representatives')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['representative_id']);
            $table->unsignedBigInteger('representative_id')->nullable(false)->change();
            $table->foreign('representative_id')->references('id')->on('representatives')->onDelete('cascade');
        });
    }
};
