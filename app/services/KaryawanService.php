<?php
class KaryawanService {
    private $karyawan;

    public function __construct(Karyawan $karyawan) {
        $this->karyawan = $karyawan;
    }

    public function getAll() {
        $stmt = $this->karyawan->getAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $this->karyawan->id = $id;
        return $this->karyawan->getById();
    }

    public function create(array $input) {
        $this->validateRequired($input, ['employee_id', 'name', 'department', 'position', 'salary']);
        $input = $this->sanitize($input);
        
        $this->karyawan->employee_id = $input['employee_id'];
        $this->karyawan->name = $input['name'];
        $this->karyawan->department = $input['department'];
        $this->karyawan->position = $input['position'];
        $this->karyawan->salary = (float)$input['salary'];
        $this->karyawan->hire_date = $input['hire_date'] ?? null;
        $this->karyawan->email = $input['email'] ?? null;
        $this->karyawan->phone = $input['phone'] ?? null;
        
        if ($this->karyawan->create()) {
            return [
                'id' => $this->karyawan->id,
                'employee_id' => $this->karyawan->employee_id,
                'name' => $this->karyawan->name,
                'department' => $this->karyawan->department,
                'position' => $this->karyawan->position,
                'salary' => $this->karyawan->salary,
                'hire_date' => $this->karyawan->hire_date,
                'email' => $this->karyawan->email,
                'phone' => $this->karyawan->phone
            ];
        }
        throw new Exception('Failed to add employee');
    }

    public function update(int $id, array $input) {
        $this->validateRequired($input, ['employee_id', 'name', 'department', 'position', 'salary']);
        $input = $this->sanitize($input);
        
        $this->karyawan->id = $id;
        $this->karyawan->employee_id = $input['employee_id'];
        $this->karyawan->name = $input['name'];
        $this->karyawan->department = $input['department'];
        $this->karyawan->position = $input['position'];
        $this->karyawan->salary = (float)$input['salary'];
        $this->karyawan->hire_date = $input['hire_date'] ?? null;
        $this->karyawan->email = $input['email'] ?? null;
        $this->karyawan->phone = $input['phone'] ?? null;
        
        if (!$this->karyawan->update()) {
            throw new Exception('Failed to update employee data');
        }
    }

    public function delete(int $id) {
        $this->karyawan->id = $id;
        if (!$this->karyawan->delete()) {
            throw new Exception('Failed to delete employee data');
        }
    }

    private function validateRequired(array $input, array $requiredFields): void {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field]) || empty(trim($input[$field]))) {
                $missing[] = $field;
            }
        }
        if (!empty($missing)) {
            throw new Exception('Required fields: ' . implode(', ', $missing));
        }
    }

    private function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}