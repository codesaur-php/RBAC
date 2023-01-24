<?php

namespace codesaur\RBAC;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class Permissions extends Model
{
    function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        
        $this->setColumns([
           (new Column('id', 'bigint', 8))->auto()->primary()->unique()->notNull(),
           (new Column('name', 'varchar', 128))->unique()->notNull(),
            new Column('module', 'varchar', 128, 'general'),
            new Column('description', 'varchar', 255),
           (new Column('alias', 'varchar', 64))->notNull(),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
            new Column('created_by', 'bigint', 8),
            new Column('updated_at', 'datetime'),
            new Column('updated_by', 'bigint', 8)
        ]);
        
        $this->setTable('rbac_permissions', 'utf8_unicode_ci');
    }
    
    function __initial()
    {
        parent::__initial();
        
        $table = $this->getName();
        
        $this->setForeignKeyChecks(false);
        $this->exec("ALTER TABLE $table ADD CONSTRAINT {$table}_fk_created_by FOREIGN KEY (created_by) REFERENCES rbac_accounts(id) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->exec("ALTER TABLE $table ADD CONSTRAINT {$table}_fk_updated_by FOREIGN KEY (updated_by) REFERENCES rbac_accounts(id) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->setForeignKeyChecks(true);
    }
}
