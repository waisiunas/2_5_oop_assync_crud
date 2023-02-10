<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', '2_5_oop_crud');
class Database
{
    private $conn;


    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->conn) {
            die('Not Connected' . $this->conn->error);
        }
    }

    public function show($table) {
        $sql = "SELECT * FROM `$table`";
        $result = $this->conn->query($sql);
        $records = $result->fetch_all(MYSQLI_ASSOC);
        return $records;
    }

    public function show_single($table, $id) {
        $sql = "SELECT * FROM `$table` WHERE `id` = $id";
        $result = $this->conn->query($sql);
        $records = $result->fetch_assoc();
        return $records;
    }

    public function is_email_already_exists($email, $id = null) {
        if (empty($id)) {
            $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        } else {
            $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `id` != $id";
        }
        
        $result = $this->conn->query($sql);
        return $result->num_rows != 0 ? true : false;
    }

    public function insert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);

        $columns = implode('`, `', $columns);
        $values = implode("', '", $values);

        $sql = "INSERT INTO `$table`(`$columns`) VALUES ('$values')";
    
        return $this->conn->query($sql) ? true : false;
    }

    public function update($table, $data, $id) {
        $pairs = [];
        foreach($data as $key => $value) {
            $pairs[] = "`" . $key . "` = '" . $value . "'"; 
        }
        $pairs = implode(", ", $pairs);
        $sql = "UPDATE `$table` SET $pairs WHERE `id` = $id";
    
        return $this->conn->query($sql) ? true : false;
    }
    
    public function delete($table, $id) {
        $sql = "DELETE FROM `$table` WHERE `id` = $id";
    
        return $this->conn->query($sql) ? true : false;
    }

}

$database = new Database;

