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
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('profile_photo')->nullable()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('email'); // if missing
            $table->rememberToken()->after('password'); // if missing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('remember_token');
        });
    }
};


