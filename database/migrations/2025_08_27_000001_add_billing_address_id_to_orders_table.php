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
        if (!Schema::hasColumn('orders', 'billing_address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('billing_address_id')->nullable()->after('total');
                $table->index('billing_address_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'billing_address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('billing_address_id');
            });
        }
    }
};


