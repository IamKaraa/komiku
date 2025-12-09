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
            // Drop the foreign key constraint
            $table->dropForeign(['approved_by']);
            // Make the column nullable
            $table->foreignId('approved_by')->nullable()->change();
            // Add the foreign key constraint back
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comic', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['approved_by']);
            // Make the column not nullable
            $table->foreignId('approved_by')->nullable(false)->change();
            // Add the foreign key constraint back
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
