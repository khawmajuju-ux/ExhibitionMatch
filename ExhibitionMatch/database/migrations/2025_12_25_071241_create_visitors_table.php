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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id('Visitor_ID');
            $table->string('visitor_name', 150);
            $table->string('visitor_company', 150)->nullable();
            $table->string('visitor_position', 100)->nullable();
            $table->string('visitor_contact', 100)->nullable();
            // NOTE: Foreign key is added in a later migration to avoid
            // creation-order issues (this migration runs before `problemtags`).
            $table->foreignId('Tag_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
