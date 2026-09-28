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
        Schema::create('problemtags', function (Blueprint $table) {
            $table->id('Tag_ID');
            $table->string('tag_name', 100);
            $table->string('tag_color', 20)->nullable();
            $table->text('tag_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problemtags');
    }
};
