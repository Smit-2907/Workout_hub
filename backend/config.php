<?php
// backend/config.php
// Production configuration - Keep this out of version control in a real scenario

define('DB_HOST', 'localhost');
define('DB_NAME', 'workout_hub');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// General Site Settings
define('SITE_NAME', 'Workout Hub 2.0');
define('BASE_URL', 'http://localhost/workout/Workout_hub/');

// Security Settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('JWT_SECRET', 'your_super_secret_key_123_456_789');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
// ini_set('session.cookie_secure', 1); // Enable this if using HTTPS
