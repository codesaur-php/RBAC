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
           (new Column('user_id', 'bigint', 20))->notNull()->constraints('CONSTRAINT rbac_user_role_fk_user_id FOREIGN KEY (user_id) REFERENCES rbac_accounts(id) ON DELETE CASCADE ON UPDATE CASCADE'),
           (new Column('role_id', 'bigint', 20))->notNull()->constraints('CONSTRAINT rbac_user_role_fk_role_id FOREIGN KEY (role_id) REFERENCES rbac_roles(id) ON DELETE CASCADE ON UPDATE CASCADE'),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->constraints('CONSTRAINT rbac_user_role_fk_created_by FOREIGN KEY (created_by) REFERENCES rbac_accounts(id) ON DELETE SET NULL ON UPDATE CASCADE'),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->constraints('CONSTRAINT rbac_user_role_fk_updated_by FOREIGN KEY (updated_by) REFERENCES rbac_accounts(id) ON DELETE SET NULL ON UPDATE CASCADE')
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
        $query = "INSERT INTO $table(id,created_at,user_id,role_id)"
                . " VALUES(1,'$nowdate',1,1)";
        $this->exec($query);
    }
}
