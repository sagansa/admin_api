<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel token Sanctum hidup di DB auth terpusat (mysql_auth → sagansa_user),
// diken bersama panel apps/admin. Tabelnya kemungkinan sudah dibuat oleh
// aplikasi lain — migrasi ini hanya membuat bila belum ada (idempotent).
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('mysql_auth')->hasTable('personal_access_tokens')) {
            return;
        }

        Schema::connection('mysql_auth')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Jangan drop: tabel dipakai bersama aplikasi lain (panel apps/admin).
    }
};
