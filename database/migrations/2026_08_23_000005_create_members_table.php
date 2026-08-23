<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname');
            $table->string('slug')->unique();
            $table->string('student_number'); // NIM / Nomor Absen
            $table->string('class_name'); // Kelas (e.g. MI 3B, MI A)
            $table->string('major'); // Jurusan (e.g. Manajemen Informatika)
            $table->string('generation'); // Angkatan (e.g. 2024, 2025, 2026)
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('instagram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->boolean('is_instagram_public')->default(false);
            $table->boolean('is_whatsapp_public')->default(false);
            $table->boolean('privacy_agreed')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected', 'hidden'])->default('pending');
            $table->foreignId('invitation_id')->nullable()->constrained('member_invitations')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
