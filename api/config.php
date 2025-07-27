<?php
/**
 * Configuration de production Oupafamilly
 * Optimisé pour hébergement OVH
 */

// Configuration MySQL OVH
define('DB_HOST', 'oupafaj803.mysql.db');
define('DB_NAME', 'oupafaj803');
define('DB_USER', 'oupafaj803');
define('DB_PASS', 'e6f3R9s3N6dYwjqU');
define('DB_CHARSET', 'utf8mb4');

// Configuration CORS
define('ALLOWED_ORIGINS', [
    'https://oupafamilly.com',
    'https://www.oupafamilly.com',
    'http://oupafamilly.com',  // Ajout HTTP pour les redirections
    'http://www.oupafamilly.com'
]);

// Configuration JWT
define('JWT_SECRET', 'oupafamilly-production-secret-2024-secure-key');
define('JWT_EXPIRE_HOURS', 24);

// Configuration générale
define('TIMEZONE', 'Europe/Paris');
define('DEBUG_MODE', false);

// Configuration uploads
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGES', ['jpg', 'jpeg', 'png', 'gif']);

// Configuration rate limiting
define('RATE_LIMIT_REQUESTS', 100);
define('RATE_LIMIT_WINDOW', 3600);

// Configuration Redis (désactivé pour OVH mutualisé)
define('REDIS_ENABLED', false);

// Configuration logs
define('LOG_LEVEL', 'ERROR');
define('LOG_FILE', __DIR__ . '/logs/app.log');

// Jeux supportés
define('SUPPORTED_GAMES', ['cs2', 'lol', 'wow', 'sc2', 'minecraft']);

// Rôles utilisateurs
define('USER_ROLES', ['admin', 'moderator', 'member']);
define('USER_STATUSES', ['active', 'inactive', 'pending']);

// Statuts tournois
define('TOURNAMENT_STATUSES', ['draft', 'open', 'in_progress', 'completed', 'cancelled']);
define('TOURNAMENT_TYPES', ['elimination', 'bracket', 'round_robin']);

// Valeurs par défaut nouveaux utilisateurs
define('DEFAULT_COINS', 100);
define('DEFAULT_ELO', 1200);
define('DEFAULT_LEVEL', 1);

// Configuration email
define('MAIL_HOST', 'ssl0.ovh.net');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'contact@oupafamilly.com');
define('MAIL_PASSWORD', ''); // À configurer selon vos besoins
define('MAIL_FROM', 'contact@oupafamilly.com');
define('MAIL_FROM_NAME', 'Oupafamilly');

// Fuseau horaire
date_default_timezone_set(TIMEZONE);

// Configuration erreurs production
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Création dossier logs
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}
?>