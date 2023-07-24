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
        Schema::table('user_audit_logs', function (Blueprint $table) {
            Schema::table('user_audit_logs', function (Blueprint $table) {
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('user_agent')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_audit_logs', function (Blueprint $table) {
            //
        });
    }
};
