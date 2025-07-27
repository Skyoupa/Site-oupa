<?php
/**
 * Script de diagnostic et réparation pour l'admin Oupafamilly
 * À exécuter via https://oupafamilly.com/admin-fix.php
 */

// Headers CORS
header('Content-Type: text/html; charset=utf-8');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'https://oupafamilly.com',
    'https://www.oupafamilly.com',
    'http://oupafamilly.com',
    'http://www.oupafamilly.com'
];

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Chargement des dépendances
require_once 'api/config.php';
require_once 'api/database.php';
require_once 'api/auth.php';

echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Admin Fix - Oupafamilly</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #0a0e1a; color: #fff; }
        .result { margin: 10px 0; padding: 15px; border-radius: 8px; }
        .success { background: #059669; border-left: 5px solid #10b981; }
        .error { background: #dc2626; border-left: 5px solid #ef4444; }
        .info { background: #3b82f6; border-left: 5px solid #60a5fa; }
        .warning { background: #d97706; border-left: 5px solid #f59e0b; }
        pre { background: #1f2937; padding: 15px; border-radius: 5px; overflow-x: auto; margin: 10px 0; }
        .test-section { margin: 30px 0; border: 1px solid #374151; padding: 20px; border-radius: 8px; }
        h1, h2 { color: #3b82f6; }
        .credentials { background: #1f2937; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>';

echo '<h1>🔧 Diagnostic Admin Oupafamilly</h1>';

function addResult($message, $type = 'info') {
    echo "<div class='result $type'>$message</div>";
}

// Test 1: Vérification de la connexion à la base de données
echo '<div class="test-section">';
echo '<h2>🗄️ Test 1: Connexion Base de Données</h2>';

try {
    global $db;
    addResult('✅ Connexion à la base de données réussie', 'success');
    
    // Vérifier que les tables existent
    $tables = $db->fetchAll("SHOW TABLES");
    addResult('📋 Tables trouvées: ' . count($tables), 'info');
    
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<small>• $tableName</small><br>";
    }
    
} catch (Exception $e) {
    addResult('❌ Erreur de connexion BDD: ' . $e->getMessage(), 'error');
}
echo '</div>';

// Test 2: Vérification des utilisateurs existants
echo '<div class="test-section">';
echo '<h2>👥 Test 2: Utilisateurs Existants</h2>';

try {
    $users = $db->fetchAll("SELECT id, username, email, role, status FROM users ORDER BY created_at DESC LIMIT 10");
    addResult('👤 Utilisateurs trouvés: ' . count($users), 'info');
    
    if ($users) {
        echo '<pre>';
        foreach ($users as $user) {
            echo "ID: {$user['id']}\n";
            echo "Username: {$user['username']}\n";
            echo "Email: {$user['email']}\n";
            echo "Role: {$user['role']}\n";
            echo "Status: {$user['status']}\n";
            echo "---\n";
        }
        echo '</pre>';
    }
    
    // Vérifier spécifiquement l'admin
    $admin = $db->fetch("SELECT * FROM users WHERE role = 'admin' OR email = 'admin@oupafamilly.com' LIMIT 1");
    if ($admin) {
        addResult('✅ Utilisateur admin trouvé: ' . $admin['username'], 'success');
    } else {
        addResult('⚠️ Aucun utilisateur admin trouvé', 'warning');
    }
    
} catch (Exception $e) {
    addResult('❌ Erreur récupération utilisateurs: ' . $e->getMessage(), 'error');
}
echo '</div>';

// Test 3: Test du hashage des mots de passe
echo '<div class="test-section">';
echo '<h2>🔐 Test 3: Système de Hash</h2>';

try {
    $testPassword = 'Oupafamilly2024!';
    $hash = Auth::hashPassword($testPassword);
    addResult('✅ Hash généré avec succès', 'success');
    
    $verify = Auth::verifyPassword($testPassword, $hash);
    if ($verify) {
        addResult('✅ Vérification du hash réussie', 'success');
    } else {
        addResult('❌ Échec de la vérification du hash', 'error');
    }
    
    // Tester avec le hash existant si admin existe
    $admin = $db->fetch("SELECT hashed_password FROM users WHERE email = 'admin@oupafamilly.com' LIMIT 1");
    if ($admin) {
        $verifyExisting = Auth::verifyPassword($testPassword, $admin['hashed_password']);
        if ($verifyExisting) {
            addResult('✅ Hash admin existant compatible', 'success');
        } else {
            addResult('❌ Hash admin existant incompatible - PROBLÈME IDENTIFIÉ', 'error');
        }
    }
    
} catch (Exception $e) {
    addResult('❌ Erreur test hash: ' . $e->getMessage(), 'error');
}
echo '</div>';

// Test 4: Action de réparation
echo '<div class="test-section">';
echo '<h2>🔧 Test 4: Réparation Admin</h2>';

if (isset($_GET['repair']) && $_GET['repair'] === 'admin') {
    try {
        // Supprimer l'admin existant s'il y en a un
        $db->query("DELETE FROM users WHERE email = 'admin@oupafamilly.com'");
        addResult('🗑️ Ancien admin supprimé', 'info');
        
        // Créer le nouvel admin
        $adminId = Auth::generateUUID();
        $profileId = Auth::generateUUID();
        
        $db->beginTransaction();
        
        // Créer l'utilisateur admin avec le nouveau hash
        $newHash = Auth::hashPassword('Oupafamilly2024!');
        $db->query(
            "INSERT INTO users (id, username, email, hashed_password, role, status, is_verified, created_at) 
             VALUES (?, ?, ?, ?, 'admin', 'active', 1, NOW())",
            [
                $adminId,
                'admin',
                'admin@oupafamilly.com',
                $newHash
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
        addResult('✅ ADMIN RÉPARÉ AVEC SUCCÈS !', 'success');
        
        // Test de connexion immédiat
        $testUser = $db->fetch(
            "SELECT * FROM users WHERE email = 'admin@oupafamilly.com'"
        );
        
        if ($testUser && Auth::verifyPassword('Oupafamilly2024!', $testUser['hashed_password'])) {
            addResult('✅ Test de connexion admin réussi', 'success');
        } else {
            addResult('❌ Test de connexion admin échoué', 'error');
        }
        
    } catch (Exception $e) {
        if (isset($db)) $db->rollback();
        addResult('❌ Erreur réparation: ' . $e->getMessage(), 'error');
    }
} else {
    addResult('💡 Pour réparer l\'admin, ajoutez ?repair=admin à l\'URL', 'info');
    addResult('<a href="?repair=admin" style="color: #60a5fa;">🔧 RÉPARER L\'ADMIN MAINTENANT</a>', 'warning');
}
echo '</div>';

// Test 5: Test API Login
echo '<div class="test-section">';
echo '<h2>🌐 Test 5: Test API Login</h2>';

echo '<div id="login-test">
    <button onclick="testLogin()" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        🧪 Tester la Connexion Admin
    </button>
    <div id="login-result" style="margin-top: 15px;"></div>
</div>';

echo '<script>
async function testLogin() {
    const resultDiv = document.getElementById("login-result");
    resultDiv.innerHTML = "⏳ Test en cours...";
    
    try {
        const response = await fetch("/api/auth/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                username: "admin@oupafamilly.com",
                password: "Oupafamilly2024!"
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            resultDiv.innerHTML = `<div class="result success">✅ Connexion API réussie !<br>Token: ${data.access_token.substring(0, 50)}...</div>`;
        } else {
            resultDiv.innerHTML = `<div class="result error">❌ Erreur API: ${data.error || "Erreur inconnue"}</div>`;
        }
        
    } catch (error) {
        resultDiv.innerHTML = `<div class="result error">❌ Erreur réseau: ${error.message}</div>`;
    }
}
</script>';

echo '</div>';

// Informations finales
echo '<div class="credentials">';
echo '<h2>🔑 Identifiants Admin</h2>';
echo '<strong>Email:</strong> admin@oupafamilly.com<br>';
echo '<strong>Mot de passe:</strong> Oupafamilly2024!<br>';
echo '<strong>Username:</strong> admin<br><br>';
echo '<em>⚠️ Ces identifiants sont pour l\'administration. Changez-les en production !</em>';
echo '</div>';

echo '<div class="result info">';
echo '💡 <strong>Pour supprimer ce fichier après réparation :</strong><br>';
echo '1. Testez la connexion admin<br>';
echo '2. Supprimez admin-fix.php du serveur<br>';
echo '3. Videz le cache du navigateur';
echo '</div>';

echo '</body></html>';
?>