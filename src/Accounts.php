<?php

namespace codesaur\RBAC;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class Accounts extends Model
{
    function __construct(PDO $conn)
    {
        parent::__construct($conn);
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('username', 'varchar', 65))->unique(),
            new Column('password', 'varchar', 255, ''),
            new Column('first_name', 'varchar', 50),
            new Column('last_name', 'varchar', 50),
            new Column('phone', 'varchar', 50),
            new Column('address', 'varchar', 200),
            new Column('city', 'varchar', 100),
            new Column('country', 'varchar', 4),
           (new Column('external', 'varchar', 255))->unique(),
           (new Column('email', 'varchar', 65))->unique(),
            new Column('photo', 'varchar', 255),
            new Column('legend', 'int', 4, 1),
            new Column('code', 'varchar', 6),
            new Column('status', 'tinyint', 1, 0),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey('rbac_accounts(id) ON UPDATE CASCADE'),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey('rbac_accounts(id) ON UPDATE CASCADE')
        ));
        
        $this->setTable('rbac_accounts', 'utf8_unicode_ci');
    }

    // <editor-fold defaultstate="collapsed" desc="initial">
    function __initial()
    {
        $table = $this->getName();        
        if ($table !== 'rbac_accounts') {
            return;
        }
        
        $now_date = date('Y-m-d H:i:s');
        $password = $this->quote(password_hash('password', PASSWORD_BCRYPT));
        $query = "INSERT INTO $table (id,created_at,username,password,first_name,last_name,email,status)"
                . " VALUES (1,'$now_date','admin',$password,'Admin','System','admin@example.com',1)";
        
        $this->exec($query);
    }
    // </editor-fold>
}
