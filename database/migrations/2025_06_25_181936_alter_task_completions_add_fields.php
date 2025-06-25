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
        Schema::table('task_completions', function (Blueprint $table) {
            $table->string('media_url')->nullable()->after('photo_url');
            $table->enum('media_type', ['photo', 'video', 'audio', 'text'])->nullable()->after('media_url');
            $table->text('text_proof')->nullable()->after('media_type');
            $table->json('checked_options')->nullable()->after('text_proof');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('points_awarded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {
            $table->dropColumn(['media_url', 'media_type', 'text_proof', 'checked_options', 'status']);
        });
    }
};
