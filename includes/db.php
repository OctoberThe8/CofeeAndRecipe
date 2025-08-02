<?php
require_once 'config.php';

// Database connection class
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

// PDO handler and statement properties
    private $dbh;
    private $stmt;
    private $error;
    
// Constructor: creates a database connection using PDO
    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        try {
            // Attempt to create a new PDO instance
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            // Catch connection errors and display the message
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
    // Prepare an SQL query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    // Bind values to query parameters with automatic type detection
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    // Execute the prepared statement
    public function execute() {
        return $this->stmt->execute();
    }
    // Fetch all rows as an array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    // Fetch a single row as an object
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    // Get the number of affected rows
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    // Get the ID of the last inserted row
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
?>