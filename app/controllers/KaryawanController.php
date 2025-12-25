<?php
class KaryawanController extends Controller {
    private $service;

    public function __construct() {
        $db = (new Database())->getConnection();
        $karyawanModel = new Karyawan($db);
        $this->service = new KaryawanService($karyawanModel);
    }

    public function index() {
        try {
            $result = $this->service->getAll();
            $this->success($result, 'Employee data retrieved successfully');
        } catch (Exception $e) {
            $this->error('Failed to retrieve data: ' . $e->getMessage(), 500);
        }
    }

    public function show($id) {
        try {
            $result = $this->service->getById((int)$id);
            if ($result) {
                $this->success($result, 'Employee data found');
            } else {
                $this->error('Employee data not found', 404);
            }
        } catch (Exception $e) {
            $this->error('Failed to retrieve data: ' . $e->getMessage(), 500);
        }
    }

    public function create() {
        $input = $this->getJsonInput();
        if (!$input) {
            $this->error('Invalid JSON data', 400);
        }
        try {
            $created = $this->service->create($input);
            $this->success($created, 'Employee added successfully', 201);
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    public function update($id) {
        if (!$id || !is_numeric($id)) {
            $this->error('Invalid ID', 400);
        }
        $input = $this->getJsonInput();
        if (!$input) {
            $this->error('Invalid JSON data', 400);
        }
        try {
            $this->service->update((int)$id, $input);
            $this->success(null, 'Employee data updated successfully');
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id) {
        if (!$id || !is_numeric($id)) {
            $this->error('Invalid ID', 400);
        }
        try {
            $this->service->delete((int)$id);
            $this->success(null, 'Employee deleted successfully');
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }
}