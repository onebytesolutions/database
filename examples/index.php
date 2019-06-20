<?php
use OneByteSolutions\Database\Database,
    OneByteSolutions\Database\Adapters\PdoAdapter;
    
/**
 * Use Case Example
 *
 * @category  Example
 * @description This is a default autoloader for the entire onebytesolutions library
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2019
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database
 * @version   1.1
 */

// set db config
$db = [
    'host' => 'localhost',
    'user' => 'db-username',
    'pass' => 'db-password',
    'database' => 'database-name'
];

// try to connect to the database
try {
    $database = new Database(new PdoAdapter($db));
    $database->connect();
}catch (\Exception $e){
    echo "Unable to connect: ".$e->getMessage();
}

try {

    // example of updating a row
    $database->updateRowWhere("user", ["lastLogin" => time()], "id", 1);

    // example of fetching a query as an array
    $sql = "SELECT * FROM users LIMIT 30";
    $params = [];
    $results = $database->queryToArray($sql, $params);
    
    echo '<pre>'.print_r($results,true).'</pre>';
    
    // example of inserting a new user, with transactions
    $database->beginTransaction();
    try {
        $row = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.org'
        ];
        $id = $database->insertRow("users", $row);
        $database->commit();
        
        echo 'inserted id: '.$id;
    } catch(\Exception $e){
        echo 'insert failure';
        $database->rollBack();
    }
    
    // example of deleting a record
    $database->deleteWhere("users", "id", 1);
    
    // standard query
    $database->run("UPDATE users SET name = :name", ['name' => 'Alex Doe']);
    
}catch (\Exception $e){
    echo "SQL Error: ".$e->getMessage();
}






