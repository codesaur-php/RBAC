<?php

namespace codesaur\RBAC;

class Role
{
    public array $permissions = [];

    public function getPermissions(\PDO $pdo, int $role_id)
    {
        $permissions = new Permissions($pdo);
        $role_perm = new RolePermission($pdo);
        $sql = "SELECT t2.name, t2.alias FROM {$role_perm->getName()} t1"
            . " INNER JOIN {$permissions->getName()} t2 ON t1.permission_id=t2.id"
            . ' WHERE t1.role_id=:role_id AND t1.is_active=1';
        $pdo_stmt = $role_perm->prepare($sql);
        $pdo_stmt->execute([':role_id' => $role_id]);
        if ($pdo_stmt->rowCount()) {
            while ($row = $pdo_stmt->fetch(\PDO::FETCH_ASSOC)) {
                $this->permissions["{$row['alias']}_{$row['name']}"] = true;
            }
        }
        
        return $this;
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions[$permissionName] ?? false;
    }
}
