<?php

use Illuminate\Database\Migrations\Migration;

// STUB NO-OP — skema personal_access_tokens di mysql_auth (sagansa_user)
// dimiliki oleh project migration terpusat:
//   sagansa/services/migration/database/migrations/auth/
//     - 2024_01_01_000000_initial_auth_tables.php
//     - 2026_05_20_021000_ensure_auth_personal_access_tokens_table_exists.php
// Ketentuan desain: semua migration ada di services/migration, tidak di aplikasi.
// File ini sengaja dibuat no-op supaya aman bila ter-deploy, dan HARUS DIHAPUS
// sebelum commit.
return new class extends Migration
{
    public function up(): void
    {
        // no-op — lihat komentar di atas
    }

    public function down(): void
    {
        // no-op — lihat komentar di atas
    }
};
