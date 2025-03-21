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
        Schema::create('domain_renewals', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Auto, Manual
            $table->dateTime('renewed_date');
            $table->string('status'); // Pending, Success, Failed
            $table->dateTime('next_due_date');
            $table->text('notes')->nullable();
            $table->foreignId('domain_subscription_id')->constrained();
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
        Schema::dropIfExists('domain_renewals');
    }
};
