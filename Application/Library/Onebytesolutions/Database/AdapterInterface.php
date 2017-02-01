<?php
namespace Application\Library\Onebytesolutions\Database;

/**
 * Adapter Interface
 *
 * @category  Database Access
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database-library
 * @version   1.0
 */
interface AdapterInterface {
    /**
     * Connect to database
     */
    public function connect();
    
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