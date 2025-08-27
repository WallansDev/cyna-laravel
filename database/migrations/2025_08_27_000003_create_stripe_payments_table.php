<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_intent_id')->unique();
            $table->string('status')->nullable();
            $table->integer('amount')->nullable(); // en centimes
            $table->string('currency', 10)->nullable();
            $table->json('metadata')->nullable();
            $table->string('applied_promotion_code')->nullable();
            $table->string('applied_coupon_id')->nullable();
            $table->string('applied_coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable(); // en EUR
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_payments');
    }
};


