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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vrm', 7)->nullable()->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('vin')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('engine_size')->nullable();
            $table->string('engine_code')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('maximum_bhp')->nullable();
            $table->string('color')->nullable();
            $table->string('body_style')->nullable();
            $table->string('body_type')->nullable();
            $table->string('number_of_doors')->nullable();
            $table->string('seat_capacity')->nullable();
            $table->string('wheel_plan')->nullable();
            $table->string('aspiration')->nullable();
            $table->string('transmission')->nullable();
            $table->string('co2_emissions')->nullable();
            $table->string('gearbox_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
