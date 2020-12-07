<?php

class Core_Model
{
    /**
     * @var Core_Database
     */
    public $db;

     /**
      * Save the static instance of this class for singleton model
      */
    private static $instance;

    /**
     * Method used to return the actual instance of the model
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Build the new model with the Databse dependency created
     */
    public function __construct() {
        $this->setDb(new Core_Database());
    }

    /**
     * Execute a select statement validating the operation and returning all dataset
     */
    public function select($sql)
    {
        try{
            if (stripos($sql, 'SELECT') === false) {
                throw new Exception("You need to use this method only for SELECT queries. Check execute method for other operations.");
            }

            $this->begin();

            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

            // If the query failed then throw error showing the problem to log on sql error file
            if (!$stmt->execute()) {
                throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
            }

            // Get all the resultset for the executed query
            $result = $stmt->fetchAll();

            $this->commit();

            return $result;

        } catch (Exception $e){

            // Rollback the operation
            $this->rollback();

            // Log the error on file log and continue the process
            Core_Logger::SQLlog($e);
        }
    }

    /**
     * Execute a select statement validating the operation and returning only one result
     */
    public function selectOne($sql)
    {
        try{
            if (stripos($sql, 'SELECT') === false) {
                throw new Exception("You need to use this method only for SELECT queries. Check execute method for other operations.");
            }

            $this->begin();

            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

            // If the query failed then throw error showing the problem to log on sql error file
            if (!$stmt->execute()) {
                throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
            }

            $result = $stmt->fetch();

            $this->commit();

            return $result;

        } catch (Exception $e){

            $this->rollback();

            Core_Logger::SQLlog($e);
        }
    }

    /**
     * Execute an insert, update or delete query, validating the operation
     * and returning the id if insert or the number of updated/deleted rows
     */
    public function execute($sql)
    {
        try{

            if (
                stripos($sql, 'INSERT') === false &&
                stripos($sql, 'UPDATE') === false &&
                stripos($sql, 'DELETE') === false
            ){
                $msg = "You need to use this method only for INSERT, UPDATE or DELETE queries.";
                $msg .= "Check select method for SELECT operations.";
                throw new Exception($msg);
            }

            $this->begin();

            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

            // If the query failed then throw error showing the problem to log on sql error file
            if (!$stmt->execute()) {
                throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
            }

            if (stripos($sql, 'INSERT') !== false) {
                $id = $this->db->lastInsertId();
            } elseif (stripos($sql, 'UPDATE') !== false) {
                $id = $stmt->rowCount();
            } elseif (stripos($sql, 'DELETE') !== false) {
                $id = $stmt->rowCount();
            } else {
                $msg = "You need to use this method only for INSERT, UPDATE or DELETE queries. ";
                $msg .= "Check select method for SELECT operations.";
                throw new Exception($msg);
            }

            $this->commit();

            return $id;

        } catch (Exception $e){

            $this->rollback();

            Core_Logger::SQLlog($e);
        }
    }

    /**
     * Open the statement
     */
    public function begin()
    {
        $stmt = $this->db->prepare("BEGIN");
        $stmt->execute();
        $stmt = NULL;
    }

    /**
     * Commit the actual operation
     */
    public function commit()
    {
        $stmt = $this->db->prepare("COMMIT");
        $stmt->execute();
        $stmt = NULL;
    }

    /**
     * Rollback the actual operation in case of error
     */
    public function rollback()
    {
        $stmt = $this->db->prepare("ROLLBACK");
        $stmt->execute();
        $stmt = NULL;
    }

    /**
     * @param Core_Database $db
     */
    public function setDb(Core_Database $db)
    {
        $this->db = $db;
    }

    /**
     * Execute a select statement validating the operation and returning all dataset
     */
    public function selectInTransaction($sql)
    {
        if (stripos($sql, 'SELECT') === false) {
            throw new Exception("You need to use this method only for SELECT queries. Check execute method for other operations.");
        }

        $stmt = $this->db->prepare($sql);
        $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

        // If the query failed then throw error showing the problem to log on sql error file
        if (!$stmt->execute()) {
            throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
        }

        // Get all the resultset for the executed query
        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * Execute a select statement validating the operation and returning only one result
     */
    public function selectOneInTransaction($sql)
    {
        if (stripos($sql, 'SELECT') === false) {
            throw new Exception("You need to use this method only for SELECT queries. Check execute method for other operations.");
        }

        $stmt = $this->db->prepare($sql);
        $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

        // If the query failed then throw error showing the problem to log on sql error file
        if (!$stmt->execute()) {
            throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
        }

        $result = $stmt->fetch();

        return $result;
    }

    /**
     * Execute an insert, update or delete query, validating the operation
     * and returning the id if insert or the number of updated/deleted rows
     */
    public function executeInTransaction($sql)
    {
        if (
            stripos($sql, 'INSERT') === false &&
            stripos($sql, 'UPDATE') === false &&
            stripos($sql, 'DELETE') === false
        ){
            $msg = "You need to use this method only for INSERT, UPDATE or DELETE queries.";
            $msg .= "Check select method for SELECT operations.";
            throw new Exception($msg);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->setFetchMode(Core_Database::FETCH_ASSOC);

        // If the query failed then throw error showing the problem to log on sql error file
        if (!$stmt->execute()) {
            throw new Exception("Execute failed:" . $stmt->errorInfo()[2] . "in query: \n$sql");
        }

        if (stripos($sql, 'INSERT') !== false) {
            $id = $this->db->lastInsertId();
        } elseif (stripos($sql, 'UPDATE') !== false) {
            $id = $stmt->rowCount();
        } elseif (stripos($sql, 'DELETE') !== false) {
            $id = $stmt->rowCount();
        } else {
            $msg = "You need to use this method only for INSERT, UPDATE or DELETE queries. ";
            $msg .= "Check select method for SELECT operations.";
            throw new Exception($msg);
        }

        return $id;
    }
}
