<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'stripe_payment_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('stripe_payment_id')->nullable()->after('billing_address_id');
                $table->index('stripe_payment_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'stripe_payment_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('stripe_payment_id');
            });
        }
    }
};


