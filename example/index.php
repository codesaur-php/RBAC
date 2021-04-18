<?php

/* DEV: v1.2021.03.22
 * 
 * This is an example script!
 */

require_once '../vendor/autoload.php';

use codesaur\RBAC\Accounts;
use codesaur\RBAC\RBACUser;

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

try {
    $dsn = 'mysql:host=localhost;charset=utf8';
    $username = 'root';
    $passwd = '';
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    
    $pdo = new PDO($dsn, $username, $passwd, $options);
    echo 'connected to mysql...<br/>';
    
    $database = 'rbac_example';
    if ($_SERVER['HTTP_HOST'] === 'localhost'
            && in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))
    ) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $database COLLATE " . $pdo->quote('utf8_unicode_ci'));
    }

    $pdo->exec("USE $database");
    echo "starting to using database [$database]!<br/>";

    $accounts = new Accounts($pdo);
    $user = 'admin';
    $account = $accounts->getRowBy(array('username' => $user));
    if (!$account) {
        throw new Exception(__CLASS__ . ": User {$user} not found!");
    }
    var_dump(array('account' => $account));

    $rbacUser = new RBACUser($pdo, $account['id']);
    var_dump(((array)$rbacUser)['role'], json_encode((array)$rbacUser, JSON_PRETTY_PRINT));

    echo $rbacUser->hasRole('system_coder') ? 'This user is system coder!' : 'This user doesn\'t have coder role!';
} catch (Exception $ex) {
    die('<br />{' . date('Y-m-d H:i:s') . '} Error[' . $ex->getCode() . '] => ' . $ex->getMessage());
}
