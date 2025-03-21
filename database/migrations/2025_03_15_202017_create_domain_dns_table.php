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
        Schema::create('domain_dns', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('value');
            $table->integer('ttl');
            $table->integer('priority')->nullable();
            $table->foreignId('domain_id')->constrained();
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
        Schema::dropIfExists('domain_dns');
    }
};
