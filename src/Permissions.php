<?php

namespace codesaur\RBAC;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class Permissions extends Model
{
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('name', 'varchar', 128))->notNull(),
            new Column('module', 'varchar', 128, 'general'),
            new Column('description', 'varchar', 256),
           (new Column('alias', 'varchar', 64))->notNull(),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
            new Column('created_by', 'bigint', 20),
            new Column('updated_at', 'datetime'),
            new Column('updated_by', 'bigint', 20)
        ));
        
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

        if ($table != 'rbac_permissions') {
            return;
        }
        
        $nowdate = date('Y-m-d H:i:s');
        $query = "INSERT INTO $table(created_at,alias,module,name,description) "
                . "VALUES('$nowdate','system','system','system_settings',''),"
                . "('$nowdate','system','account','account_index',''),"
                . "('$nowdate','system','account','account_insert',''),"
                . "('$nowdate','system','account','account_update',''),"
                . "('$nowdate','system','account','account_delete',''),"
                . "('$nowdate','system','account','account_initial',''),"
                . "('$nowdate','system','account','account_rbac',''),"
                . "('$nowdate','system','account','account_organization_set',''),"
                . "('$nowdate','system','organization','organization_index',''),"
                . "('$nowdate','system','organization','organization_insert',''),"
                . "('$nowdate','system','organization','organization_update',''),"
                . "('$nowdate','system','organization','organization_delete',''),"
                . "('$nowdate','system','content','content_index',''),"
                . "('$nowdate','system','content','content_insert',''),"
                . "('$nowdate','system','content','content_update',''),"
                . "('$nowdate','system','content','content_delete',''),"
                . "('$nowdate','system','content','content_initial',''),"
                . "('$nowdate','system','localization','localization_index',''),"
                . "('$nowdate','system','localization','localization_insert',''),"
                . "('$nowdate','system','localization','localization_update',''),"
                . "('$nowdate','system','localization','localization_delete',''),"
                . "('$nowdate','system','localization','localization_initial',''),"
                . "('$nowdate','system','documentation','documentation',''),"
                . "('$nowdate','system','localization','logger','')";
        $this->exec($query);
    }
}
