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
        Schema::create('reservation_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('reservation_id');
            $table->unsignedBiginteger('client_id');


            $table->foreign('reservation_id')->references('id')
                 ->on('reservations')->onDelete('cascade');
            $table->foreign('client_id')->references('id')
                ->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_client');
    }
};
