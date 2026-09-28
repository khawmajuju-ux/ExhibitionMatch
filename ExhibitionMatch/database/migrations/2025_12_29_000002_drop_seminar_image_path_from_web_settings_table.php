<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            if (Schema::hasColumn('web_settings', 'seminar_image_path')) {
                $table->dropColumn('seminar_image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('web_settings', 'seminar_image_path')) {
                $table->string('seminar_image_path')->nullable()->after('landing_image_path');
            }
        });
    }
};


