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
        Schema::create('domain_provider_payments', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->foreignId('domain_provider_id')->constrained();
            $table->foreignId('domain_payment_id')->constrained();
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
        Schema::dropIfExists('domain_provider_payments');
    }
};
