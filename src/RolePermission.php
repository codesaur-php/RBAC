<?php

namespace codesaur\RBAC;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class RolePermission extends Model
{
    function __construct(PDO $conn)
    {
        parent::__construct($conn);
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('role_id', 'bigint', 20))->notNull()->foreignKey('rbac_roles(id)'),
           (new Column('permission_id', 'bigint', 20))->notNull()->foreignKey('rbac_permissions(id)'),
           (new Column('alias', 'varchar', 16))->notNull(),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey('rbac_accounts(id)'),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey('rbac_accounts(id)')
        ));
        
        $this->setCreateTable('rbac_role_perm');
    }
}
