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
        Schema::create('domain_provider_tld', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_tld_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_provider_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->nullable();          // costo base del proveedor
            $table->json('rules')->nullable();                    // restricciones (min/max años, privacy, etc.)
            $table->timestamps();
            $table->unique(['domain_tld_id', 'domain_provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('create_domain_provider_tlds');
    }
};
