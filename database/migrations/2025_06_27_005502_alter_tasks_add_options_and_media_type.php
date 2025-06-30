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
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('media_type', ['photo', 'audio', 'video', 'text'])->nullable()->after('replicate');
            $table->json('options')->nullable()->after('media_type');
            $table->boolean('is_bonus')->default(false)->after('options');
            $table->integer('max_completions')->nullable()->after('is_bonus');
            $table->boolean('is_required')->default(true)->after('max_completions');
            $table->time('start_time')->nullable()->after('is_required');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
