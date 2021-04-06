<?php

namespace codesaur\RBAC;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class Roles extends Model
{
    function __construct(PDO $conn)
    {
        parent::__construct($conn);
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('name', 'varchar', 128))->notNull(),
            new Column('description', 'varchar', 255),
           (new Column('alias', 'varchar', 16))->notNull(),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey('rbac_accounts(id) ON UPDATE CASCADE'),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey('rbac_accounts(id) ON UPDATE CASCADE')
        ));
        
        $this->setTable('rbac_roles', 'utf8_unicode_ci');
    }
    
    function __initial()
    {
        $table = $this->getName();        
        if ($table !== 'rbac_roles') {
            return;
        }
        
        $nowdate = date('Y-m-d H:i:s');
        $query =  "INSERT INTO $table (id,created_at,name,description,alias) "
                . "VALUES (1,'$nowdate','coder','Coder can do anything!','system')";

        $this->exec($query);
    }
}
