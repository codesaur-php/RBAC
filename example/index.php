<?php

namespace codesaur\RBAC\Example;

/* DEV: v1.2021.03.22
 * 
 * This is an example script!
 */

\ini_set('display_errors', 'On');
\error_reporting(\E_ALL);

require_once '../vendor/autoload.php';

use codesaur\RBAC\Accounts;
use codesaur\RBAC\RBACUser;

try {
    $dsn = 'mysql:host=localhost;charset=utf8';
    $username = 'root';
    $passwd = '';
    $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
    
    $pdo = new \PDO($dsn, $username, $passwd, $options);
    echo 'connected to mysql...<br/>';
    
    $database = 'rbac_example';
    if (\in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $database COLLATE " . $pdo->quote('utf8_unicode_ci'));
    }

    $pdo->exec("USE $database");
    echo "starting to use database [$database]<br/>";

    $accounts = new Accounts($pdo);
    $user = 'admin';
    $account = $accounts->getRowBy(['username' => $user]);
    if (!$account) {
        throw new \Exception(__CLASS__ . ": User $user not found!");
    }
    echo '<pre>';
    \print_r(['account' => $account]);
    echo '</pre><hr>';

    $rbacUser = new RBACUser($pdo, $account['id']);
    echo '<pre>';
    \print_r((array) $rbacUser);
    echo '</pre><hr>';

    echo $rbacUser->hasRole('system_coder') ? 'This user is system coder.' : 'This user doesn\'t have coder role.';
} catch (\Throwable $th) {
    die('<br />{' . date('Y-m-d H:i:s') . '} Error[' . $th->getCode() . '] => ' . $th->getMessage());
}
