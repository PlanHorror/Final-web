<?php 
class Database {
    private $host = 'localhost';
    private $db_name = 'final';
    private $username = 'root';
    private $password = '';
    private $conn;
    public function __construct() {
        $this->connect();
    }
    // Connect to the database
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
    // Insert data into the database
    public function create($data, $table) {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (:' . implode(', :', $keys) . ')';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
    }
    // Read data from the database by table name
    public function read($table) {
        $sql = 'SELECT * FROM ' . $table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Read data from the database by table name and id
    public function readUseId($id, $table) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Find data from the database by table name and column name
    public function find($column, $value, $table) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $column . ' = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch();
    }
    // Count data from the database by table name and column name
    public function count($column, $value, $table) {
        $sql = 'SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $column . ' = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchColumn();
    }
    // Read use column name and value
    public function readUseColumn($table,$column,$value) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $column . ' = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }
    public function query($sql) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function update($data, $table, $id) {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = 'UPDATE ' . $table . ' SET ' . implode(' = ?, ', $keys) . ' = ? WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge($values, [$id]));
    }
}