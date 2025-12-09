<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comic', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false);
            $table->integer('price')->nullable();
        });
    }

    public function down()
    {
        Schema::table('comic', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'price']);
        });
    }
};
