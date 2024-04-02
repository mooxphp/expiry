<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('item');
            $table->string('link');
            $table->dateTime('expired_at');
            $table->dateTime('notified_at');
            $table->string('notified_to');
            $table->dateTime('escalated_at');
            $table->string('escalated_to');
            $table->string('handled_by');
            $table->dateTime('done_at');
            $table->unsignedBigInteger('expiry_monitor_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiries');
    }
};
