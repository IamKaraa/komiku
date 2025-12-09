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
            if (Schema::hasColumn('comic', 'apptoved_at')) {
                $table->dropColumn('apptoved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comic', function (Blueprint $table) {
            $table->timestamp('apptoved_at');
        });
    }
};
