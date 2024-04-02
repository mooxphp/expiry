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
        Schema::table('expiries', function (Blueprint $table) {
            $table
                ->foreign('expiry_monitor_id')
                ->references('id')
                ->on('expiry_monitors')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expiries', function (Blueprint $table) {
            $table->dropForeign(['expiry_monitor_id']);
        });
    }
};
