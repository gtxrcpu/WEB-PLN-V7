<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Roles - Struktur Baru
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $leader     = Role::firstOrCreate(['name' => 'leader']);
        $petugas    = Role::firstOrCreate(['name' => 'petugas']);
        $inspector  = Role::firstOrCreate(['name' => 'inspector']);

        // 2) Permissions
        $perms = [
            // Superadmin permissions
            'manage all units',
            'manage users',
            'manage roles',
            'manage references',
            'manage templates',
            'view all data',
            'export all data',
            
            // Leader permissions (Admin Unit)
            'manage unit users',
            'approve kartu',
            'manage unit equipment',
            'view unit data',
            'export unit data',
            
            // Petugas permissions
            'create inspection',
            'edit own inspection',
            'view own inspection',
            
            // Inspector permissions
            'view inspections',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // 3) Map permissions → roles
        
        // Superadmin - Full access
        $superadmin->givePermissionTo([
            'manage all units',
            'manage users',
            'manage roles',
            'manage references',
            'manage templates',
            'view all data',
            'export all data',
            'approve kartu',
            'manage unit equipment',
            'create inspection',
            'edit own inspection',
            'view own inspection',
            'view inspections',
        ]);
        
        // Leader - Admin di unit masing-masing
        $leader->givePermissionTo([
            'manage unit users',
            'approve kartu',
            'manage unit equipment',
            'view unit data',
            'export unit data',
            'create inspection',
            'edit own inspection',
            'view own inspection',
        ]);
        
        // Petugas - Input data saja
        $petugas->givePermissionTo([
            'create inspection',
            'edit own inspection',
            'view own inspection',
        ]);
        
        // Inspector - Read-only
        $inspector->givePermissionTo([
            'view inspections',
        ]);

        $this->command->info('✅ Roles & Permissions created successfully!');
    }
}
