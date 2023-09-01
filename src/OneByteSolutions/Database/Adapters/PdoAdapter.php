<?php

namespace OneByteSolutions\Database\Adapters;

use PDO,
    OneByteSolutions\Database\AdapterInterface;

/**
 * PDO Adapter
 *
 * @category  Database Access
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2023
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database
 * @version   1.6
 */
class PdoAdapter implements AdapterInterface {

    private $connection;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $database;

    /**
     * Instantiate the class
     * 
     * @param Array[host, port, user, pass, database] $config 
     */
    public function __construct($config) {
        $this->host = $config['host'];
        $this->port = (isset($config['port']) ? $config['port'] : '');
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->database = $config['database'];
    }

    /**
     * Connect to database
     */
    public function connect() {
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ($this->port ? ";port=" . $this->port : "") . ";dbname=" . $this->database, $this->user, $this->pass, array(PDO::ATTR_PERSISTENT => true));
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get Connection
     */
    public function connection() {
        return $this->connection;
    }

    /**
     * Run a query
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Boolean
     */
    public function run($query, $params = array()) {
        try {
            $statement = $this->connection->prepare($query);
            foreach ($params as $key => $value) {
                $statement->bindValue(":" . $key, $value);
            }
            $statement->execute();

            return $statement;
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Run a query and return the results as an array
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Array
     */
    public function queryToArray($query, $params = array()) {
        $result = [];

        try {
            $statement = $this->connection->prepare($query);
            foreach ($params as $key => $value) {
                $statement->bindValue(":" . $key, $value);
            }
            $statement->execute();

            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get last insert id
     * 
     * @return Integer
     */
    public function getLastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * Begin Transaction
     * 
     * @return Boolean
     */
    public function beginTransaction() {
        $this->connection->beginTransaction();
    }

    /**
     * Commit Transaction
     * 
     * @return Boolean
     */
    public function commit() {
        $this->connection->commit();
    }

    /**
     * Roll-back Transaction
     * 
     * @return Boolean
     */
    public function rollBack() {
        $this->connection->rollBack();
    }
}
