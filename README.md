<?php

Database Library - A dependency injection based Database Class
<hr>
### Table of Contents

**[Initialization](#initialization)**  
**[Insert Query](#insert-query)**  
**[Update Query](#update-query)**  
**[Select Query](#select-query)**  
**[Delete Query](#delete-query)**  
**[Raw Query](#raw-query)**  
## About

This software was developed during my free time and is free to use.

### Installation
To utilize this class, include the autolaoder.

```php
require_once("Application/Library/Onebytesolutions/Autoloader.php");
```

### Initialization
Simple initialization with utf8 charset set by default:
```php
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
```

### Insert Query
Simple example
```php
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
```

### Update Query
```php
// example of updating a row
$database->updateRowWhere("user", ["lastLogin" => time()], "id", 1);
```

### Select Query
// example of fetching a query as an array
```php
$sql = "SELECT * FROM users LIMIT 30";
$params = [];
$results = $database->queryToArray($sql, $params);

echo '<pre>'.print_r($results,true).'</pre>';
```

### Delete Query
// example of deleting a row
```php
$database->deleteWhere("users", "id", 1);
```

### Raw Query
// example of running raw sql
```php
$database->run("UPDATE users SET name = :name", ['name' => 'Alex Doe']);
```