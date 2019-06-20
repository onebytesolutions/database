<?php
namespace OneByteSolutions\Database;

use OneByteSolutions\Database\AdapterInterface;

/**
 * Database Class
 *
 * @category  Database Access
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2019
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database
 * @version   1.1
 */
class Database {

    protected $adapter;

    /**
     * Instantiate a new database class
     * @param AdapterInterface $adapter 
     */
    public function __construct(AdapterInterface $adapter) {
        $this->adapter = $adapter;
    }

    /**
     * Connect to database
     */
    public function connect() {
        $this->adapter->connect();
    }

    /**
     * Run a query
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Boolean
     */
    public function run($sql, $params = array()) {
        return $this->adapter->run($sql, $params);
    }

    /**
     * Run a query and return the results as an array
     * 
     * @param String $sql 
     * @param Array $params 
     * 
     * @return Array
     */
    public function queryToArray($sql, $params = array()) {
        return $this->adapter->queryToArray($sql, $params);
    }

    /**
     * Get Row Where
     * 
     * @param String $table 
     * @param Array $where 
     * 
     * @return Array
     */
    public function getRowWhereArray($table, $where) {
        $whereClause = "";
        $count = 0;
        foreach ($where as $key => $value) {
            if (++$count != sizeof($where)) {
                $whereClause .= "`" . $key . "` = :" . $key . " AND ";
            } else {
                $whereClause .= "`" . $key . "` = :" . $key . " ";
            }
        }
        $query = "SELECT * FROM " . $table . " WHERE " . $whereClause . "";

        return $this->queryToArray($query, $where);
    }

    /**
     * Get Row Where (short-cut to getRowWhereArray with a single where value)
     * 
     * @param String $table 
     * @param String $where 
     * @param Integer/String $equals 
     * 
     * @return Array
     */
    public function getRowWhere($table, $where, $equals) {
        return $this->getRowWhereArray($table, [$where => $equals]);
    }

    /**
     * Insert Row

     *  FORMAT:
     *  $row = array("column_name_1" => "column_value_1", "column_name_2" => "column_value_2", ...)
     * 
     * @param String $table 
     * @param Array $row 
     * @param String $ignoreDuplicate Flag to ignore on duplicate key
     * 
     * @return Boolean
     */
    public function insertRow($table, $row, $ignoreDuplicate = false) {
        $columns = "";
        $values = "";

        $count = 0;
        foreach ($row as $key => $value) {
            if (++$count != sizeof($row)) {
                $columns .= "`" . $key . "`, ";
                $values .= ":" . $key . ", ";
            } else {
                $columns .= "`" . $key . "`";
                $values .= ":" . $key;
            }
        }

        $query = "INSERT " . ($ignoreDuplicate ? "IGNORE" : '') . " INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";

        $result = $this->adapter->run($query, $row);
        if ($result) {
            return $this->adapter->getLastInsertId();
        } else {
            return false;
        }
    }

   /**
     * Insert Batch Row

     *  FORMAT:
     *  $row = array("column_name_1" => "column_value_1", "column_name_2" => "column_value_2", ...)
     * 
     * @param String $table 
     * @param Array [$rows]
     * @param String $ignoreDuplicate Flag to ignore on duplicate key
     * 
     * @return Boolean
     */
    public function insertRowBatch($table, $rows, $ignoreDuplicate = false) {
        $columns = "";
        $values = "";

        $count = 0;
        foreach ($rows[0] as $key => $value) {
            if (++$count != sizeof($rows[0])) {
                $columns .= "`" . $key . "`, ";
            } else {
                $columns .= "`" . $key . "`";
            }
        }

        $params = [];
        $valueRows = [];

        $v = 0;
        for ($i = 0; $i < count($rows); $i++) {
            $count = 0;
            $values = "(";
            foreach ($rows[$i] as $key => $value) {
                $col = 'v' . $v;

                if ($value == '') {
                    $col = "''";
                } else {
                    $params[$col] = $value;
                    $col = ':' . $col;
                    $v++;
                }

                if (++$count != sizeof($rows[$i])) {
                    $values .= $col . ", ";
                } else {
                    $values .= $col;
                }
            }

            $values .= ")";
            $valueRows[] = $values;
        }

        $query = "INSERT " . ($ignoreDuplicate ? "IGNORE" : '') . " INTO " . $table . " (" . $columns . ") VALUES  " . implode(", ", $valueRows);

        $result = $this->adapter->run($query, $params);
        if ($result) {
            return $this->adapter->getLastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Update Row Where
     *
     *  FORMAT:
     *  $row = array("column_name_1" => "column_value_1", "column_name_2" => "column_value_2", ...)
     * 
     * @param String $table 
     * @param Array $row 
     * @param Array $where 
     * 
     * @return Boolean
     */
    public function updateRowWhereArray($table, $row, $where) {
        $params = [];
        $values = "";
        $whereClause = "";

        $count = 0;
        foreach ($row as $key => $value) {
            if (++$count != sizeof($row)) {
                $values .= "`" . $key . "` = :c" . $key . ", ";
            } else {
                $values .= "`" . $key . "` = :c" . $key . " ";
            }

            $params['c' . $key] = $value;
        }

        $count = 0;
        foreach ($where as $key => $value) {
            if (++$count != sizeof($where)) {
                $whereClause .= "`" . $key . "` = :v" . $key . " AND ";
            } else {
                $whereClause .= "`" . $key . "` = :v" . $key . " ";
            }
            $params['v' . $key] = $value;
        }

        $query = "UPDATE " . $table . " SET " . $values . " WHERE " . $whereClause;
        return $this->adapter->run($query, $params);
    }

    /**
     * Update Row Where (short-cut to updateRowWhereArray with a single where value)
     *
     * @param String $table 
     * @param Array $row 
     * @param String $where 
     * @param Integer/String $equals 
     * 
     * @return Boolean
     */
    public function updateRowWhere($table, $row, $where, $equals) {
        return $this->updateRowWhereArray($table, $row, array($where => $equals));
    }

    /**
     * Delete Where
     * 
     * @param String $table 
     * @param Array $where 
     * 
     * @return Boolean
     */
    public function deleteWhereArray($table, $where) {
        $whereClause = "";
        $count = 0;
        foreach ($where as $key => $value) {
            if (++$count != sizeof($where)) {
                $whereClause .= "`" . $key . "` = :" . $key . " AND ";
            } else {
                $whereClause .= "`" . $key . "` = :" . $key . " ";
            }
        }

        $query = "DELETE FROM " . $table . " WHERE " . $whereClause . "";
        return $this->adapter->run($query, $where);
    }

    /**
     * Delete Where (short-cut to deleteWhere with a single where value)
     * 
     * @param String $table 
     * @param String $where 
     * @param Integer/String $equals 
     * 
     * @return Boolean
     */
    public function deleteWhere($table, $where, $equals) {
        return $this->deleteWhereArray($table, [$where => $equals]);
    }

    /**
     * Get last insert id
     * 
     * @return Integer
     */
    public function getLastInsertId() {
        return $this->adapter->getLastInsertId();
    }

    /**
     * Begin Transaction
     * 
     * @return Boolean
     */
    public function beginTransaction() {
        return $this->adapter->beginTransaction();
    }

    /**
     * Commit Transaction
     * 
     * @return Boolean
     */
    public function commit() {
        return $this->adapter->commit();
    }

    /**
     * Roll-back Transaction
     * 
     * @return Boolean
     */
    public function rollBack() {
        return $this->adapter->rollBack();
    }

}
