<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->index('is_visible');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->index(['is_visible', 'type']);
            $table->index('album_id');
            $table->index('created_at');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->index(['status', 'is_visible']);
            $table->index('class_name');
            $table->index('generation');
        });

        Schema::table('member_invitations', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropIndex(['is_visible']);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['is_visible', 'type']);
            $table->dropIndex(['album_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex(['status', 'is_visible']);
            $table->dropIndex(['class_name']);
            $table->dropIndex(['generation']);
        });

        Schema::table('member_invitations', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['expires_at']);
        });
    }
};
