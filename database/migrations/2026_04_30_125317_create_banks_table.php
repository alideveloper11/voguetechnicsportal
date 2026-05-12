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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites');
            $table->string('name');
            $table->string('account_title');
            $table->string('account_number')->unique();
            $table->string('branch_name')->nullable();
            $table->string('sort_code');
            $table->boolean('is_vat')->nullable()->default(false);
            $table->decimal('vat', 10, 2)->default(0.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
