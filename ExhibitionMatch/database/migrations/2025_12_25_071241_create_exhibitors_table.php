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
        Schema::create('exhibitors', function (Blueprint $table) {
            $table->id('Ex_ID');
            $table->string('ex_companyName', 150);
            $table->string('ex_companyEmail', 150)->nullable();
            $table->string('ex_companyPhone', 30)->nullable();
            // NOTE: Foreign key is added in a later migration to avoid
            // creation-order issues (this migration runs before `problemtags`).
            $table->foreignId('Tag_id')->nullable();
            $table->string('ex_website', 255)->nullable();
            $table->string('ex_logo', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitors');
    }
};
