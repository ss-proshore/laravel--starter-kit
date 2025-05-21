<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permissions to the database and optionally assign to super admin';

    protected array $permissions = [
        'view users', 'create users', 'edit users', 'delete users',
        'view roles', 'create roles', 'edit roles', 'delete roles',
        'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
        'view dashboard', 'view activity'
    ];
    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $createdCount = 0;
            $existingCount = 0;

            foreach ($this->permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

                if ($permission->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $existingCount++;
                }
                // Assign all permissions to super_admin
                $superAdminRole->syncPermissions(Permission::all());
            }
            $this->info("Created $createdCount new permissions. $existingCount already existed. Also, assigned all permissions to super_admin role.");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->error("Error seeding permissions: {$e->getMessage()}");
        }
    }
}
