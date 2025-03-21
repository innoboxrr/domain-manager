<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_payments', function (Blueprint $table) {
            $table->id();
            $table->string('processor'); // Stripe, PayPal, etc.
            $table->string('transaction_id'); // ID from the processor
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->foreignId('domain_renewal_id')->constrained();
            $table->foreignId('domain_payment_method_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain_payments');
    }
};
