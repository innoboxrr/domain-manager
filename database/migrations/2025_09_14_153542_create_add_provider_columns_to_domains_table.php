<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// migration: add provider columns to domains
return new class extends Migration {
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('provider_ref')->nullable();      // ID/Order/Operation del registrador
            $table->string('provider_status')->nullable();   // purchasing|active|failed|pendingVerification...
            $table->json('nameservers')->nullable();         // si aplica
            $table->boolean('privacy')->default(true);
            $table->boolean('auto_renew')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->unique('name');
        });
    }
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['provider_ref','provider_status','nameservers','privacy','auto_renew','expires_at']);
        });
    }
};
