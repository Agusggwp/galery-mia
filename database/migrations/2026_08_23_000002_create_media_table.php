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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('albums')->onDelete('cascade');
            $table->string('google_drive_id')->nullable()->index();
            $table->string('name');
            $table->string('slug');
            $table->string('mime_type');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->text('thumbnail_url')->nullable();
            $table->text('drive_url')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
