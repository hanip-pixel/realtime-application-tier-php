<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// SIMPLE ROUTING - HARDCODED
try {
    require_once '../app/config/Config.php';
    require_once '../app/config/Database.php';
    require_once '../app/core/Model.php';
    require_once '../app/models/Karyawan.php';
    require_once '../app/services/KaryawanService.php';
    require_once '../app/core/Controller.php';
    require_once '../app/controllers/KaryawanController.php';
    
    $controller = new KaryawanController();
    
    // Get path from URL
    $requestUri = $_SERVER['REQUEST_URI'];
    $basePath = '/application-tier-php/public/';
    $path = str_replace($basePath, '', $requestUri);
    $path = trim($path, '/');
    $pathParts = explode('/', $path);
    
    // Determine method
    $method = $_SERVER['REQUEST_METHOD'];
    $id = isset($pathParts[1]) && is_numeric($pathParts[1]) ? $pathParts[1] : null;
    
    switch ($method) {
        case 'GET':
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;
            
        case 'POST':
            $controller->create();
            break;
            
        case 'PUT':
        case 'PATCH':
            if ($id) {
                $controller->update($id);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID required']);
            }
            break;
            
        case 'DELETE':
            if ($id) {
                $controller->delete($id);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID required']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}