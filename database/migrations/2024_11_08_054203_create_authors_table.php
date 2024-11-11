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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Add Relationship to articles table
        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');

        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
        });
    }
};
