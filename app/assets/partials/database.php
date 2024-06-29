<?php
// Database.php

class Database {
    private $host = 'localhost';
    private $dbname = 'todolist';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Constructor to establish connection
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    // Get the database connection
    public function getConnection() {
        return $this->conn;
    }
}
?>
