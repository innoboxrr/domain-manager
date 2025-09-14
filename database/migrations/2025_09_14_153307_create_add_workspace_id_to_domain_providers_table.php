<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// migration: add workspace_id & meta to domain_providers
return new class extends Migration {
    public function up(): void
    {
        Schema::table('domain_providers', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable()->after('id')->constrained();
            $table->string('driver')->default('aws');    // 'aws' | 'namecheap' | otros
            $table->json('secrets')->nullable();         // credenciales cifradas
            $table->json('settings')->nullable();        // flags (auto_privacy, auto_renew, etc.)
            $table->index(['workspace_id', 'driver']);
        });
    }
    public function down(): void
    {
        Schema::table('domain_providers', function (Blueprint $table) {
            $table->dropColumn(['workspace_id','driver','secrets','settings']);
        });
    }
};
