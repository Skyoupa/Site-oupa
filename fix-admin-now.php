<?php
/**
 * SCRIPT DE RÉPARATION RAPIDE ADMIN
 * À exécuter une seule fois pour créer l'admin
 */

// Headers pour éviter les erreurs
header('Content-Type: application/json; charset=utf-8');

// Chargement des dépendances
require_once 'api/config.php';
require_once 'api/database.php';
require_once 'api/auth.php';

try {
    global $db;
    
    // Supprimer tous les admins existants
    $db->query("DELETE FROM user_profiles WHERE user_id IN (SELECT id FROM users WHERE email = 'admin@oupafamilly.com')");
    $db->query("DELETE FROM users WHERE email = 'admin@oupafamilly.com'");
    
    // Créer le nouvel admin avec BCRYPT forcé
    $adminId = Auth::generateUUID();
    $profileId = Auth::generateUUID();
    
    $db->beginTransaction();
    
    // Hash BCRYPT simple et compatible
    $hashedPassword = password_hash('Oupafamilly2024!', PASSWORD_BCRYPT, ['cost' => 10]);
    
    // Créer l'utilisateur admin
    $db->query(
        "INSERT INTO users (id, username, email, hashed_password, role, status, is_verified, created_at) 
         VALUES (?, ?, ?, ?, 'admin', 'active', 1, NOW())",
        [
            $adminId,
            'admin',
            'admin@oupafamilly.com',
            $hashedPassword
        ]
    );
    
    // Créer le profil admin
    $db->query(
        "INSERT INTO user_profiles (id, user_id, display_name, bio, coins, created_at) 
         VALUES (?, ?, ?, ?, ?, NOW())",
        [
            $profileId,
            $adminId,
            'Administrateur Oupafamilly',
            'Administrateur de la communauté Oupafamilly',
            10000
        ]
    );
    
    $db->commit();
    
    // Test immédiat
    $testUser = $db->fetch("SELECT * FROM users WHERE email = 'admin@oupafamilly.com'");
    $passwordTest = password_verify('Oupafamilly2024!', $testUser['hashed_password']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Admin créé avec succès',
        'admin_id' => $adminId,
        'password_test' => $passwordTest ? 'VALID' : 'INVALID',
        'hash_used' => 'BCRYPT',
        'timestamp' => date('c')
    ]);
    
} catch (Exception $e) {
    if (isset($db)) $db->rollback();
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('c')
    ]);
}
?>