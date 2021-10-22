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
                . "VALUES('$nowdate','system','system','system_mailer',''),"
                . "('$nowdate','system','account','account_index',''),"
                . "('$nowdate','system','account','account_retrieve',''),"
                . "('$nowdate','system','account','account_insert',''),"
                . "('$nowdate','system','account','account_update',''),"
                . "('$nowdate','system','account','account_delete',''),"
                . "('$nowdate','system','account','account_initial',''),"
                . "('$nowdate','system','account','account_rbac',''),"
                . "('$nowdate','system','account','account_newbie_index',''),"
                . "('$nowdate','system','account','account_forgot_index',''),"
                . "('$nowdate','system','account','account_organization_set',''),"
                . "('$nowdate','system','organization','org_index',''),"
                . "('$nowdate','system','organization','org_retrieve',''),"
                . "('$nowdate','system','organization','org_insert',''),"
                . "('$nowdate','system','organization','org_update',''),"
                . "('$nowdate','system','organization','org_delete',''),"
                . "('$nowdate','system','organization','org_initial',''),"
                . "('$nowdate','system','developer','developer_index',''),"
                . "('$nowdate','system','documentation','documentation_index',''),"
                . "('$nowdate','system','documentation','indoraptor_index',''),"
                . "('$nowdate','system','content','template_index',''),"
                . "('$nowdate','system','content','template_retrieve',''),"
                . "('$nowdate','system','content','template_insert',''),"
                . "('$nowdate','system','content','template_update',''),"
                . "('$nowdate','system','content','template_delete',''),"
                . "('$nowdate','system','content','template_initial',''),"
                . "('$nowdate','system','content','reference_index',''),"
                . "('$nowdate','system','content','reference_initial',''),"
                . "('$nowdate','system','content','pages_index',''),"
                . "('$nowdate','system','content','pages_retrieve',''),"
                . "('$nowdate','system','content','pages_insert',''),"
                . "('$nowdate','system','content','pages_update',''),"
                . "('$nowdate','system','content','pages_delete',''),"
                . "('$nowdate','system','content','files_index',''),"
                . "('$nowdate','system','content','images_index',''),"
                . "('$nowdate','system','localization','language_index',''),"
                . "('$nowdate','system','localization','language_retrieve',''),"
                . "('$nowdate','system','localization','language_insert',''),"
                . "('$nowdate','system','localization','language_update',''),"
                . "('$nowdate','system','localization','language_delete',''),"
                . "('$nowdate','system','localization','language_initial',''),"
                . "('$nowdate','system','localization','translation_index',''),"
                . "('$nowdate','system','localization','translation_retrieve',''),"
                . "('$nowdate','system','localization','translation_insert',''),"
                . "('$nowdate','system','localization','translation_update',''),"
                . "('$nowdate','system','localization','translation_delete',''),"
                . "('$nowdate','system','localization','translation_initial',''),"
                . "('$nowdate','system','localization','logger_index','')";
        $this->exec($query);
    }
}
