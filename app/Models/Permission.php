<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuids;   // ✅ Tell Permission model to use UUID

    protected $keyType = 'string';
    public $incrementing = false;
}