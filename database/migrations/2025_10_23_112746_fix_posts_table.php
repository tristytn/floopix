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
    Schema::table('posts', function (Blueprint $table) {
        if (!Schema::hasColumn('posts', 'user_id')) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        }
        if (!Schema::hasColumn('posts', 'content')) {
            $table->text('content');
        }
        if (!Schema::hasColumn('posts', 'media_url')) {
            $table->string('media_url')->nullable();
        }
        if (!Schema::hasColumn('posts', 'type')) {
            $table->enum('type', ['text', 'photo', 'video', 'gif']);
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
};
