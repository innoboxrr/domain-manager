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
        Schema::create('domain_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('renewal_cycle');
            $table->unsignedInteger('renewal_unit');
            $table->boolean('auto_renewal');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('cancel_at_end_date');
            $table->foreignId('domain_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('domain_subscriptions');
    }
};
