<?php
// backend/api.php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $nm = $_POST['name'] ?? '';
        $mail = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';
        $dob = $_POST['dob'] ?? null;
        $gen = $_POST['gender'] ?? 'male';
        $mno = $_POST['mno'] ?? '';
        
        if (empty($nm) || empty($mail) || empty($pass)) {
            die(json_encode(['status' => 'error', 'message' => 'Required fields missing']));
        }

        // Calculate age
        $age = 0;
        if ($dob) {
            $birth = new DateTime($dob);
            $today = new DateTime();
            $age = $today->diff($birth)->y;
        }

        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO consumer_mst (c_nm, c_email, c_pwd, c_dob, c_age, c_gen, c_mno) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nm, $mail, $hashed_password, $dob, $age, $gen, $mno]);
            echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    if ($action === 'login') {
        $mail = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM consumer_mst WHERE c_email = ?");
        $stmt->execute([$mail]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['c_pwd'])) {
            $_SESSION['user_id'] = $user['c_id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['c_nm'];
            echo json_encode(['status' => 'success', 'message' => 'Login success', 'role' => $user['role']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }

    if ($action === 'logout') {
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logged out']);
    }

    if ($action === 'add_exercise' && ($_SESSION['user_role'] ?? '') === 'admin') {
        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $muscle = $_POST['muscle_group'] ?? '';
        $diff = $_POST['difficulty'] ?? '';
        $video = $_POST['video_url'] ?? '';
        $image = $_POST['image_url'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO exercises (name, description, muscle_group, difficulty, video_url, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $desc, $muscle, $diff, $video, $image]);
        echo json_encode(['status' => 'success', 'message' => 'Exercise added']);
    }

    if ($action === 'delete_exercise' && ($_SESSION['user_role'] ?? '') === 'admin') {
        $id = $_POST['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Exercise deleted']);
    }

    if ($action === 'update_exercise' && ($_SESSION['user_role'] ?? '') === 'admin') {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $muscle = $_POST['muscle_group'] ?? '';
        $diff = $_POST['difficulty'] ?? '';
        $video = $_POST['video_url'] ?? '';
        $image = $_POST['image_url'] ?? '';

        $stmt = $pdo->prepare("UPDATE exercises SET name=?, description=?, muscle_group=?, difficulty=?, video_url=?, image_url=? WHERE id=?");
        $stmt->execute([$name, $desc, $muscle, $diff, $video, $image, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Exercise updated']);
    }

    if ($action === 'log_workout' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $exercise_id = $_POST['exercise_id'] ?? 0;
        $sets = $_POST['sets'] ?? 0;
        $reps = $_POST['reps'] ?? 0;
        $weight = $_POST['weight'] ?? 0;
        
        $stmt = $pdo->prepare("INSERT INTO user_logs (user_id, exercise_id, sets, reps, weight) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $exercise_id, $sets, $reps, $weight]);
        echo json_encode(['status' => 'success', 'message' => 'Workout logged successfully']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'get_categories') {
        $stmt = $pdo->query("SELECT DISTINCT muscle_group as name FROM exercises");
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'get_exercises') {
        $muscle = $_GET['muscle'] ?? '';
        $diff = $_GET['difficulty'] ?? '';
        
        $query = "SELECT * FROM exercises WHERE 1=1";
        $params = [];

        if (!empty($muscle) && $muscle !== 'All Muscle Groups') {
            $query .= " AND muscle_group = ?";
            $params[] = $muscle;
        }
        if (!empty($diff) && $diff !== 'Difficulty') {
            $query .= " AND difficulty = ?";
            $params[] = $diff;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'get_exercise_detail') {
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM exercises WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
    }

    if ($action === 'get_user_logs' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT l.*, e.name as exercise_name FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? ORDER BY l.logged_at DESC LIMIT 10");
        $stmt->execute([$user_id]);
        echo json_encode($stmt->fetchAll());
    }

    if ($action === 'check_session') {
        if (isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'authenticated', 'user' => $_SESSION['user_name'], 'role' => $_SESSION['user_role']]);
        } else {
            echo json_encode(['status' => 'unauthenticated']);
        }
    }

    if ($action === 'get_user_stats' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        // Get total workouts this week
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_logs WHERE user_id = ? AND logged_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute([$user_id]);
        $week_count = $stmt->fetch()['count'];

        // Get favorite muscle group
        $stmt = $pdo->prepare("SELECT e.muscle_group, COUNT(*) as count FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? GROUP BY e.muscle_group ORDER BY count DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $fav = $stmt->fetch();

        echo json_encode([
            'week_workouts' => $week_count,
            'favorite_focus' => $fav ? $fav['muscle_group'] : 'None'
        ]);
    }

    if ($action === 'get_recommendation' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Find muscle groups trained in the last 7 days
        $stmt = $pdo->prepare("SELECT DISTINCT e.muscle_group FROM user_logs l JOIN exercises e ON l.exercise_id = e.id WHERE l.user_id = ? AND l.logged_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute([$user_id]);
        $trained = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // All possible muscle groups
        $all_muscles = ['Chest', 'Back', 'Legs', 'Shoulders', 'Core', 'Arms'];
        
        // Find untrained muscles
        $untrained = array_values(array_diff($all_muscles, $trained));
        
        if (empty($untrained)) $target = $all_muscles[array_rand($all_muscles)];
        else $target = $untrained[array_rand($untrained)];

        // Get a random exercise from that target
        $stmt = $pdo->prepare("SELECT * FROM exercises WHERE muscle_group = ? ORDER BY RAND() LIMIT 1");
        $stmt->execute([$target]);
        $ex = $stmt->fetch();

        echo json_encode([
            'reason' => empty($trained) ? "Starting fresh? Let's begin with $target!" : "You haven't trained $target recently.",
            'exercise' => $ex
        ]);
    }
}
?>
