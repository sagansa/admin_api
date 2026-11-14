<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

// Token Sanctum disimpan di DB auth terpusat (sagansa_user) — sama dengan yang
// dipakai panel apps/admin, sehingga token dari panel valid juga di API ini.
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $connection = 'mysql_auth';
}
