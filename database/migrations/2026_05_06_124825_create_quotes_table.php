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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('website_id')->nullable()->constrained('websites');
            $table->foreignId('email_template_id')->nullable()->constrained('email_templates');
            $table->decimal('quote_amount', 10, 2)->nullable();
            $table->string('mileage')->nullable();
            $table->string('guarantee')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('offer_type')->nullable();
            $table->enum('status', ['web_inquiries', 'accepted', 'archived', 'update_quote', 'job_card', 'sold'])->default('web_inquiries');
            $table->string('quote_type')->nullable();
            $table->text('notes')->nullable();
            $table->integer('email_count')->default(0);
            $table->boolean('no_answer')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('accepted_by')->nullable()->constrained('users');
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
