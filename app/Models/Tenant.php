<?php

namespace App\Models;

use App\Models\CentralUser;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public function users()
    {
        return $this->belongsToMany(CentralUser::class, 'tenant_users', 'tenant_id', 'global_user_id', 'id', 'global_id')
            ->using(TenantPivot::class);
    }
    
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'phone',
            'title',
            'description'
        ];
    }
}