<?php

namespace codesaur\RBAC;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class UserRole extends Model
{
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('user_id', 'bigint', 20))->notNull()->foreignKey('rbac_accounts', 'id', 'CASCADE'),
           (new Column('role_id', 'bigint', 20))->notNull()->foreignKey('rbac_roles', 'id', 'CASCADE'),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey('rbac_accounts', 'id'),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey('rbac_accounts', 'id')
        ));
        
        $this->setTable('rbac_user_role');
    }
    
    public function __initial()
    {
        $table = $this->getName();        
        if ($table !== 'rbac_user_role') {
            return;
        }
        
        $nowdate = date('Y-m-d H:i:s');
        $query = "INSERT INTO $table (id,created_at,user_id,role_id)"
                . " VALUES (1,'$nowdate',1,1)";
        
        $this->exec($query);
    }
}
