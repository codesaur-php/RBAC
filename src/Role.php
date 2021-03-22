<?php

namespace codesaur\RBAC;

use PDO;

class Role
{
    public $permissions = array();

    public function getPermissions(PDO $conn, $role_id)
    {
        $permissions = new Permissions($conn);
        $role_perm = new RolePermission($conn);
        $sql =  "SELECT t2.name, t2.alias FROM {$role_perm->getName()} as t1 "
                . "JOIN {$permissions->getName()} as t2 ON t1.permission_id = t2.id "
                . 'WHERE t1.role_id = :role_id AND t1.is_active = 1';
                
        $pdo_stmt = $role_perm->prepare($sql);
        $pdo_stmt->execute(array(':role_id' => $role_id));

        $this->permissions = array();
        if ($pdo_stmt->rowCount()) {
            while ($row = $pdo_stmt->fetch(PDO::FETCH_ASSOC)) {
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
