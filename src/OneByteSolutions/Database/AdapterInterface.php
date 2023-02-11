<?php
namespace OneByteSolutions\Database;

/**
 * Adapter Interface
 *
 * @category  Database Access
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2023
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database
 * @version   1.2
 */
interface AdapterInterface {
    /**
     * Connect to database
     */
    public function connect();
    
    /**
     * Get Connection
     */
    public function connection();
    
    /**
     * Run a query
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Boolean
     */
    public function run($sql, $params);
    
    /**
     * Run a query and return the results as an array
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Array
     */
    public function queryToArray($sql, $params);
    
    /**
     * Get last insert id
     * 
     * @return Integer
     */
    public function getLastInsertId();
    
    /**
     * Begin Transaction
     * 
     * @return Boolean
     */
    public function beginTransaction();
    
    /**
     * Commit Transaction
     * 
     * @return Boolean
     */
    public function commit();
    
    /**
     * Roll-back Transaction
     * 
     * @return Boolean
     */
    public function rollBack();
}