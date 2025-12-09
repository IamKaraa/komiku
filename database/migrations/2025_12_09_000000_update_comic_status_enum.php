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
        Schema::table('comic', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'ongoing', 'completed', 'hiatus', 'pending', 'published', 'rejected'])->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comic', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft');
        });
    }
};
