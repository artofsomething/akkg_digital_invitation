<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuids;   // ✅ Tell Role model to use UUID

    protected $keyType = 'string';
    public $incrementing = false;
}