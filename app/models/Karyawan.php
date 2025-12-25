<?php
class Karyawan extends Model {
    
    public $id;
    public $employee_id;
    public $name;
    public $department;
    public $position;
    public $salary;
    public $hire_date;
    public $email;
    public $phone;

    public function __construct($db) {
        parent::__construct($db);
        $this->table = "employees";
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        return $this->executeQuery($query);
    }

    public function getById() {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->executeQuery($query, [':id' => $this->id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->employee_id = $row['employee_id'];
            $this->name = $row['name'];
            $this->department = $row['department'];
            $this->position = $row['position'];
            $this->salary = $row['salary'];
            $this->hire_date = $row['hire_date'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            return $row;
        }

        return false;
    }

    public function create() {
        $query = "INSERT INTO {$this->table} 
                  (employee_id, name, department, position, salary, hire_date, email, phone) 
                  VALUES (:employee_id, :name, :department, :position, :salary, :hire_date, :email, :phone)";

        $params = [
            ':employee_id' => $this->employee_id,
            ':name' => $this->name,
            ':department' => $this->department,
            ':position' => $this->position,
            ':salary' => $this->salary,
            ':hire_date' => $this->hire_date,
            ':email' => $this->email,
            ':phone' => $this->phone
        ];

        $stmt = $this->executeQuery($query, $params);

        if ($stmt) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE {$this->table} 
                  SET employee_id = :employee_id, 
                      name = :name, 
                      department = :department,
                      position = :position,
                      salary = :salary,
                      hire_date = :hire_date,
                      email = :email,
                      phone = :phone
                  WHERE id = :id";

        $params = [
            ':id' => $this->id,
            ':employee_id' => $this->employee_id,
            ':name' => $this->name,
            ':department' => $this->department,
            ':position' => $this->position,
            ':salary' => $this->salary,
            ':hire_date' => $this->hire_date,
            ':email' => $this->email,
            ':phone' => $this->phone
        ];

        $stmt = $this->executeQuery($query, $params);
        return $stmt->rowCount() > 0;
    }

    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->executeQuery($query, [':id' => $this->id]);
        return $stmt->rowCount() > 0;
    }
}