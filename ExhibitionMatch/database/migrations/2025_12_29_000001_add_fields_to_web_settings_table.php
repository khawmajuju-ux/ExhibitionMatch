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
        Schema::table('web_settings', function (Blueprint $table) {
            $table->string('web_title', 150)->nullable()->after('Web_ID');
            $table->string('seminar_image_path')->nullable()->after('landing_image_path');
            $table->string('seminar_map_image_path')->nullable()->after('seminar_image_path');
            $table->string('exhibitor_list_image_path')->nullable()->after('seminar_map_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn([
                'web_title',
                'seminar_image_path',
                'seminar_map_image_path',
                'exhibitor_list_image_path',
            ]);
        });
    }
};


