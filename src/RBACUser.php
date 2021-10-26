<?php

namespace codesaur\RBAC;

use PDO;
use JsonSerializable;

class RBACUser implements JsonSerializable
{
    public $role = array();
    
    public function __construct(PDO $pdo, $user_id)
    {
        $roles = new Roles($pdo);
        $user_role = new UserRole($pdo);
        $sql =  'SELECT t1.role_id, t2.name, t2.alias'
                . " FROM {$user_role->getName()} t1 INNER JOIN {$roles->getName()} t2"
                . ' ON t1.role_id=t2.id WHERE t1.user_id=:user_id AND t1.is_active=1';
        $pdo_stmt = $pdo->prepare($sql);
        $pdo_stmt->execute(array(':user_id' => $user_id));
        
        $this->role = array();
        if ($pdo_stmt->rowCount()) {
            while ($row = $pdo_stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->role["{$row['alias']}_{$row['name']}"] = (new Role())->getPermissions($pdo, $row['role_id']);
            }
        }
    }

    public function hasRole(string $roleName): bool
    {
        foreach (array_keys($this->role) as $name) {
            if ($name == $roleName) {
                return true;
            }
        }
        
        return false;
    }

    public function hasPrivilege(string $permissionName, ?string $roleName = null): bool
    {
        if (isset($roleName)) {
            if (isset($this->role[$roleName])) {
                return $this->role[$roleName]->hasPermission($permissionName);
            } else {
                return false;
            }
        }
        
        foreach ($this->role as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function jsonSerialize()
    {
        $role_permissions = array();
        
        foreach ($this->role as $name => $role) {
            if (!$role instanceof Role) {
                continue;
            }
            
            $role_permissions[$name] = array();
            
            foreach ($role->permissions as $permission => $granted) {
                $role_permissions[$name][$permission] = $granted;
            }
        }
        
        return $role_permissions;
    }
}
