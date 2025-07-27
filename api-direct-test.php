<?php
/**
 * TEST DIRECT API - Diagnostic
 */

echo "<h1>🔍 Test Direct API Oupafamilly</h1>";

// Test 1: Vérifier que PHP fonctionne
echo "<h2>1. Test PHP</h2>";
echo "✅ PHP Version: " . phpversion() . "<br>";
echo "✅ Timestamp: " . date('Y-m-d H:i:s') . "<br>";

// Test 2: Vérifier les fichiers
echo "<h2>2. Vérification des fichiers</h2>";
$files = [
    'api/index.php',
    'api/config.php', 
    'api/database.php',
    'api/auth.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file manquant<br>";
    }
}

// Test 3: Test d'inclusion des dépendances
echo "<h2>3. Test des dépendances</h2>";
try {
    require_once 'api/config.php';
    echo "✅ config.php chargé<br>";
    
    require_once 'api/database.php';
    echo "✅ database.php chargé<br>";
    
    require_once 'api/auth.php';
    echo "✅ auth.php chargé<br>";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
}

// Test 4: Test de la base de données
echo "<h2>4. Test Base de Données</h2>";
try {
    global $db;
    $result = $db->fetch("SELECT COUNT(*) as count FROM users");
    echo "✅ Connexion DB réussie<br>";
    echo "✅ Utilisateurs dans la DB: " . $result['count'] . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur DB: " . $e->getMessage() . "<br>";
}

// Test 5: Test API direct (sans routeur)
echo "<h2>5. Test API Direct</h2>";

// Simuler une requête de login
if ($_POST && isset($_POST['test_login'])) {
    try {
        $input = [
            'username' => 'admin@oupafamilly.com',
            'password' => 'Oupafamilly2024!'
        ];
        
        global $db;
        
        // Trouver l'utilisateur
        $user = $db->fetch(
            "SELECT * FROM users WHERE username = ? OR email = ?",
            [$input['username'], $input['username']]
        );
        
        if ($user) {
            echo "✅ Utilisateur trouvé: " . $user['username'] . "<br>";
            
            if (Auth::verifyPassword($input['password'], $user['hashed_password'])) {
                echo "✅ Mot de passe correct<br>";
                
                $token = Auth::createJWT($user['id'], $user['username'], $user['email'], $user['role']);
                echo "✅ Token généré: " . substr($token, 0, 50) . "...<br>";
                
            } else {
                echo "❌ Mot de passe incorrect<br>";
            }
        } else {
            echo "❌ Utilisateur non trouvé<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur test login: " . $e->getMessage() . "<br>";
    }
}

?>

<form method="POST">
    <button type="submit" name="test_login" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
        🧪 Tester Login Direct
    </button>
</form>

<h2>6. URLs de test</h2>
<p><a href="/api/" target="_blank">Test: /api/</a> - Devrait retourner du JSON</p>
<p><a href="/api/tournaments/" target="_blank">Test: /api/tournaments/</a> - Liste des tournois</p>
<p><a href="/api/community/stats" target="_blank">Test: /api/community/stats</a> - Stats communauté</p>

<h2>7. Informations serveur</h2>
<pre><?php print_r($_SERVER); ?></pre>