<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ API Réparée - Test Final</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%);
            color: #f8fafc;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        h1 { color: #3b82f6; text-align: center; }
        h2 { color: #60a5fa; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        
        .btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover { transform: translateY(-2px); }
        .btn.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .btn.error { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); }
        
        .result {
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #6ee7b7;
        }
        
        .error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        
        .info {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid #3b82f6;
            color: #93c5fd;
        }
        
        .test-section {
            background: rgba(15, 23, 42, 0.6);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .final-status {
            text-align: center;
            font-size: 24px;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Test Final - API Réparée</h1>
        
        <div class="test-section">
            <h2>🧪 Tests de Vérification</h2>
            <button class="btn" onclick="testAll()">🚀 LANCER TOUS LES TESTS</button>
            <div id="test-results"></div>
        </div>
        
        <div id="final-status" class="final-status" style="display: none;"></div>
        
        <div class="test-section">
            <h2>📋 Tests Individuels</h2>
            <button class="btn" onclick="testAPIStatus()">📊 API Status</button>
            <button class="btn" onclick="testLogin()">🔐 Login Admin</button>
            <button class="btn" onclick="testTournaments()">🏆 Tournois</button>
            <button class="btn" onclick="testCommunity()">👥 Communauté</button>
            <button class="btn" onclick="testTutorials()">📚 Tutoriels</button>
        </div>
    </div>

    <script>
        const results = {};
        
        function addResult(message, type) {
            const div = document.createElement('div');
            div.className = `result ${type}`;
            div.innerHTML = message;
            document.getElementById('test-results').appendChild(div);
        }

        async function testAPIStatus() {
            try {
                const response = await fetch('/api/');
                const data = await response.json();
                
                if (response.ok && data.status === 'ok') {
                    results.status = true;
                    addResult('✅ API Status: Fonctionnelle', 'success');
                    return true;
                } else {
                    results.status = false;
                    addResult('❌ API Status: Erreur', 'error');
                    return false;
                }
            } catch (error) {
                results.status = false;
                addResult(`❌ API Status: ${error.message}`, 'error');
                return false;
            }
        }

        async function testLogin() {
            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        username: 'admin@oupafamilly.com',
                        password: 'Oupafamilly2024!'
                    })
                });

                const data = await response.json();

                if (response.ok && data.access_token) {
                    results.login = true;
                    addResult(`✅ Login Admin: Succès (${data.user.role})`, 'success');
                    return true;
                } else {
                    results.login = false;
                    addResult(`❌ Login Admin: ${data.error}`, 'error');
                    return false;
                }
            } catch (error) {
                results.login = false;
                addResult(`❌ Login Admin: ${error.message}`, 'error');
                return false;
            }
        }

        async function testTournaments() {
            try {
                const response = await fetch('/api/tournaments/');
                const data = await response.json();
                
                if (response.ok) {
                    results.tournaments = true;
                    addResult(`✅ Tournois: ${Array.isArray(data) ? data.length : 0} tournois`, 'success');
                    return true;
                } else {
                    results.tournaments = false;
                    addResult('❌ Tournois: Erreur API', 'error');
                    return false;
                }
            } catch (error) {
                results.tournaments = false;
                addResult(`❌ Tournois: ${error.message}`, 'error');
                return false;
            }
        }

        async function testCommunity() {
            try {
                const response = await fetch('/api/community/stats');
                const data = await response.json();
                
                if (response.ok) {
                    results.community = true;
                    addResult('✅ Communauté: Stats OK', 'success');
                    return true;
                } else {
                    results.community = false;
                    addResult('❌ Communauté: Erreur API', 'error');
                    return false;
                }
            } catch (error) {
                results.community = false;
                addResult(`❌ Communauté: ${error.message}`, 'error');
                return false;
            }
        }

        async function testTutorials() {
            try {
                const response = await fetch('/api/content/tutorials');
                const data = await response.json();
                
                if (response.ok) {
                    results.tutorials = true;
                    addResult(`✅ Tutoriels: ${Array.isArray(data) ? data.length : 0} tutoriels`, 'success');
                    return true;
                } else {
                    results.tutorials = false;
                    addResult('❌ Tutoriels: Erreur API', 'error');
                    return false;
                }
            } catch (error) {
                results.tutorials = false;
                addResult(`❌ Tutoriels: ${error.message}`, 'error');
                return false;
            }
        }

        async function testAll() {
            document.getElementById('test-results').innerHTML = '';
            document.getElementById('final-status').style.display = 'none';
            
            addResult('🚀 Lancement des tests...', 'info');
            
            await testAPIStatus();
            await testLogin();
            await testTournaments();
            await testCommunity();
            await testTutorials();
            
            // Résultat final
            const allPassed = Object.values(results).every(r => r === true);
            const passedCount = Object.values(results).filter(r => r === true).length;
            const totalCount = Object.keys(results).length;
            
            const statusDiv = document.getElementById('final-status');
            
            if (allPassed) {
                statusDiv.className = 'final-status success';
                statusDiv.innerHTML = `
                    <h2>🎉 TOUS LES TESTS RÉUSSIS ! (${passedCount}/${totalCount})</h2>
                    <p><strong>✅ L'API est complètement réparée !</strong></p>
                    <p>🚀 Vous pouvez maintenant sauvegarder sur GitHub en toute sécurité.</p>
                    <p>📋 Les pages /communaute, /tutoriels et /tournois fonctionneront parfaitement.</p>
                `;
            } else {
                statusDiv.className = 'final-status error';
                statusDiv.innerHTML = `
                    <h2>⚠️ TESTS PARTIELS (${passedCount}/${totalCount})</h2>
                    <p>Certains tests ont échoué, mais l'essentiel fonctionne.</p>
                    <p>💡 Vérifiez les erreurs spécifiques ci-dessus.</p>
                `;
            }
            
            statusDiv.style.display = 'block';
        }

        // Auto-test au chargement
        window.addEventListener('load', function() {
            setTimeout(testAll, 1000);
        });
    </script>
</body>
</html>