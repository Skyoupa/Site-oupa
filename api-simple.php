<?php
/**
 * API SIMPLE DE TEST - Sans routeur
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Chargement des dépendances
require_once 'api/config.php';
require_once 'api/database.php'; 
require_once 'api/auth.php';

try {
    // Test de connexion simple
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['username']) && isset($input['password'])) {
            // Test de login
            global $db;
            
            $user = $db->fetch(
                "SELECT * FROM users WHERE username = ? OR email = ?",
                [$input['username'], $input['username']]
            );
            
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'User not found']);
                exit;
            }
            
            if (!Auth::verifyPassword($input['password'], $user['hashed_password'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid password']);
                exit;
            }
            
            if ($user['status'] !== 'active') {
                http_response_code(401);
                echo json_encode(['error' => 'Account not active']);
                exit;
            }
            
            // Créer le token
            $token = Auth::createJWT($user['id'], $user['username'], $user['email'], $user['role']);
            
            http_response_code(200);
            echo json_encode([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
            exit;
        }
    }
    
    // GET par défaut - status
    http_response_code(200);
    echo json_encode([
        'status' => 'ok',
        'message' => 'API Simple de test fonctionnelle',
        'timestamp' => date('c'),
        'endpoints' => [
            'POST' => 'Envoyer username/password pour login',
            'GET' => 'Status API'
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'timestamp' => date('c')
    ]);
}
?>