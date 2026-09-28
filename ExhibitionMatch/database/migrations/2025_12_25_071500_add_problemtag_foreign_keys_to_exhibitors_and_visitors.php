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
        Schema::table('exhibitors', function (Blueprint $table) {
            $table->foreign('Tag_id')
                ->references('Tag_ID')
                ->on('problemtags')
                ->nullOnDelete();
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->foreign('Tag_id')
                ->references('Tag_ID')
                ->on('problemtags')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitors', function (Blueprint $table) {
            $table->dropForeign(['Tag_id']);
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign(['Tag_id']);
        });
    }
};


