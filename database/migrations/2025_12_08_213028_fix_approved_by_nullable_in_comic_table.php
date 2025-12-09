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
        // Drop the foreign key constraint
        Schema::table('comic', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
        });

        // Make the column nullable
        DB::statement('ALTER TABLE comic ALTER COLUMN approved_by DROP NOT NULL');

        // Re-add the foreign key constraint
        Schema::table('comic', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('comic', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
        });

        // Make the column not nullable (set default to null for existing records first)
        DB::statement('UPDATE comic SET approved_by = 1 WHERE approved_by IS NULL');
        DB::statement('ALTER TABLE comic ALTER COLUMN approved_by SET NOT NULL');

        // Re-add the foreign key constraint
        Schema::table('comic', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
