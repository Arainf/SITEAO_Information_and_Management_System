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
            $table->enum('role', ['admin', 'moderator', 'officer', 'member', 'pending'])
                  ->default('pending')
                  ->after('email');
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->after('role');
            $table->string('avatar')->nullable()->after('status');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('committee')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'avatar', 'bio', 'committee']);
        });
    }
};
