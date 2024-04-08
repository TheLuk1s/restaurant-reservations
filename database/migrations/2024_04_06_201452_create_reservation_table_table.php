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
        Schema::create('reservation_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('reservation_id');
            $table->unsignedBiginteger('table_id');

            $table->foreign('reservation_id')->references('id')
                 ->on('reservations')->onDelete('cascade');
            $table->foreign('table_id')->references('id')
                ->on('tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_table');
    }
};
