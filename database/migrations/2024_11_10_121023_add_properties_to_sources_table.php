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
        Schema::table('sources', function (Blueprint $table) {
            $table->string('url')->nullable();
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->boolean('enabled')->default(true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->removeColumn(['url','api_key','api_secret', 'enabled']);
        });
    }
};
