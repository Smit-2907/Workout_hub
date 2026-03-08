<?php
// backend/api.php
header('Content-Type: application/json');

require_once 'db_connect.php';
require_once 'config.php';

// Prevent browser caching for security
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

/**
 * Modern Token-based Auth (JWT-style)
 */
function generateToken($user) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode([
        'user_id' => $user['c_id'],
        'role' => $user['role'],
        'name' => $user['c_nm'],
        'exp' => time() + (86400 * 30) // 30 days
    ]);
    
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verifyToken() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) return null;
        
        $header = $tokenParts[0];
        $payload = $tokenParts[1];
        $signatureProvided = $tokenParts[2];
        
        $signature = hash_hmac('sha256', $header . "." . $payload, JWT_SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if ($base64UrlSignature === $signatureProvided) {
            $data = json_decode(base64_decode($payload), true);
            if ($data['exp'] < time()) return null;
            return $data;
        }
    }
    return null;
}

// Global Auth Context
$currentUser = verifyToken();

/**
 * Production-ready JSON Response Helper
 */
function sendResponse($status, $message, $extra = []) {
    $response = array_merge(['status' => $status, 'message' => $message], $extra);
    echo json_encode($response);
    exit;
}

/**
 * Industry-level Global Error Handler
 */
set_exception_handler(function($e) {
    error_log($e->getMessage());
    sendResponse('error', 'An internal server error occurred. Please try again later.');
});

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'register') {
            $nm = strip_tags($_POST['name'] ?? '');
            $mail = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $pass = $_POST['password'] ?? '';
            $dob = $_POST['dob'] ?? null;
            $gen = $_POST['gender'] ?? 'male';
            $mno = strip_tags($_POST['mno'] ?? '');
            
            if (empty($nm) || !filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
                sendResponse('error', 'Valid name, email, and password (min 6 chars) are required.');
            }

            $age = 0;
            if ($dob) {
                $birth = new DateTime($dob);
                $today = new DateTime();
                $age = $today->diff($birth)->y;
            }

            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO consumer_mst (c_nm, c_email, c_pwd, c_dob, c_age, c_gen, c_mno) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nm, $mail, $hashed_password, $dob, $age, $gen, $mno]);
            sendResponse('success', 'Registration successful');
        }

        if ($action === 'login') {
            $mail = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $pass = $_POST['password'] ?? '';

            $stmt = $pdo->prepare("SELECT * FROM consumer_mst WHERE c_email = ?");
            $stmt->execute([$mail]);
            $user = $stmt->fetch();

            if ($user && password_verify($pass, $user['c_pwd'])) {
                $token = generateToken($user);
                sendResponse('success', 'Login successful', [
                    'token' => $token,
                    'role' => $user['role'],
                    'user' => $user['c_nm']
                ]);
            } else {
                sendResponse('error', 'Invalid email or password');
            }
        }

        if ($action === 'logout') {
            sendResponse('success', 'Logged out');
        }

        // Admin Only Actions
        if (strpos($action, 'exercise') !== false && ($currentUser['role'] ?? '') !== 'admin') {
            sendResponse('error', 'Unauthorized access');
        }

        if ($action === 'add_exercise') {
            $stmt = $pdo->prepare("INSERT INTO exercises (name, description, muscle_group, difficulty, video_url, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['muscle_group'], $_POST['difficulty'], $_POST['video_url'], $_POST['image_url']]);
            sendResponse('success', 'Exercise added');
        }

        if ($action === 'delete_exercise') {
            $stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            sendResponse('success', 'Exercise deleted');
        }

        if ($action === 'update_exercise') {
            $stmt = $pdo->prepare("UPDATE exercises SET name=?, description=?, muscle_group=?, difficulty=?, video_url=?, image_url=? WHERE id=?");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['muscle_group'], $_POST['difficulty'], $_POST['video_url'], $_POST['image_url'], $_POST['id']]);
            sendResponse('success', 'Exercise updated');
        }

        if ($action === 'log_workout' && $currentUser) {
            $stmt = $pdo->prepare("INSERT INTO user_logs (user_id, exercise_id, sets, reps, weight) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$currentUser['user_id'], $_POST['exercise_id'], $_POST['sets'], $_POST['reps'], $_POST['weight']]);
            sendResponse('success', 'Workout logged successfully');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? '';

        if ($action === 'get_categories') {
            $stmt = $pdo->query("SELECT DISTINCT muscle_group as name FROM exercises");
            echo json_encode($stmt->fetchAll());
            exit;
        }

        if ($action === 'get_exercises') {
            $muscle = $_GET['muscle'] ?? '';
            $diff = $_GET['difficulty'] ?? '';
            $query = "SELECT * FROM exercises WHERE 1=1";
            $params = [];
            if (!empty($muscle) && $muscle !== 'All Muscle Groups') { $query .= " AND muscle_group = ?"; $params[] = $muscle; }
            if (!empty($diff) && $diff !== 'Difficulty') { $query .= " AND difficulty = ?"; $params[] = $diff; }
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll());
            exit;
        }

        if ($action === 'get_exercise_detail') {
            $stmt = $pdo->prepare("SELECT * FROM exercises WHERE id = ?");
            $stmt->execute([$_GET['id'] ?? 0]);
            echo json_encode($stmt->fetch());
            exit;
        }

        if ($action === 'check_session') {
            if ($currentUser) {
                sendResponse('authenticated', 'User is logged in', ['user' => $currentUser['name'], 'role' => $currentUser['role']]);
            } else {
                sendResponse('unauthenticated', 'No active session');
            }
        }

        if ($action === 'get_user_logs' && $currentUser) {
            $stmt = $pdo->prepare("SELECT l.*, e.name as exercise_name FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? ORDER BY l.logged_at DESC LIMIT 10");
            $stmt->execute([$currentUser['user_id']]);
            echo json_encode($stmt->fetchAll());
            exit;
        }

        if ($action === 'get_user_stats' && $currentUser) {
            $user_id = $currentUser['user_id'];
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_logs WHERE user_id = ? AND logged_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stmt->execute([$user_id]);
            $week_count = $stmt->fetch()['count'];
            $stmt = $pdo->prepare("SELECT e.muscle_group, COUNT(*) as count FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? GROUP BY e.muscle_group ORDER BY count DESC LIMIT 1");
            $stmt->execute([$user_id]);
            $fav = $stmt->fetch();
            echo json_encode(['week_workouts' => $week_count, 'favorite_focus' => $fav ? $fav['muscle_group'] : 'None']);
            exit;
        }

        if ($action === 'get_recommendation' && $currentUser) {
            $stmt = $pdo->prepare("SELECT DISTINCT e.muscle_group FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? AND l.logged_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stmt->execute([$currentUser['user_id']]);
            $trained = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $all_muscles = ['Chest', 'Back', 'Legs', 'Shoulders', 'Core', 'Arms'];
            $untrained = array_values(array_diff($all_muscles, $trained));
            $target = empty($untrained) ? $all_muscles[array_rand($all_muscles)] : $untrained[array_rand($untrained)];
            $stmt = $pdo->prepare("SELECT * FROM exercises WHERE muscle_group = ? ORDER BY RAND() LIMIT 1");
            $stmt->execute([$target]);
            $ex = $stmt->fetch();
            echo json_encode(['reason' => empty($trained) ? "Starting fresh? Let's begin with $target!" : "You haven't trained $target recently.", 'exercise' => $ex]);
            exit;
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    sendResponse('error', 'A critical error occurred.');
}
