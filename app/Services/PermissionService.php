<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionService
{
    /**
     * Get all permissions grouped by their module
     * 
     * @return array
     */
    public function getGroupedPermissions(): array
    {
        $permissions = Permission::all();
        
        // Extract unique modules from permission names
        $modules = $permissions->map(function ($permission) {
            // Split permission name by space and get the last word (module name)
            $parts = explode(' ', $permission->name);
            return end($parts);
        })->unique()->values();

        // Group permissions by module
        $groupedPermissions = [];
        foreach ($modules as $module) {
            $modulePermissions = $permissions->filter(function ($permission) use ($module) {
                return str_ends_with($permission->name, $module);
            })->values();

            if ($modulePermissions->isNotEmpty()) {
                $groupedPermissions[] = [
                    'module' => ucfirst($module),
                    'permissions' => $modulePermissions,
                ];
            }
        }

        // Add any remaining permissions that don't match any module to "Others" group
        $usedPermissionIds = collect($groupedPermissions)
            ->pluck('permissions')
            ->flatten()
            ->pluck('id');

        $otherPermissions = $permissions->whereNotIn('id', $usedPermissionIds);
        
        if ($otherPermissions->isNotEmpty()) {
            $groupedPermissions[] = [
                'module' => 'Others',
                'permissions' => $otherPermissions->values(),
            ];
        }

        return $groupedPermissions;
    }
} 