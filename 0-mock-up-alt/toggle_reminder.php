<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$reminder_id = $data['reminder_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$reminder_id) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid reminder ID']);
    exit;
}

try {
    // Verify reminder belongs to user's note
    $stmt = $pdo->prepare("
        SELECT r.id, r.completed 
        FROM reminders r 
        JOIN notes n ON r.note_id = n.id 
        JOIN spaces s ON n.space_id = s.id 
        WHERE r.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$reminder_id, $user_id]);
    $reminder = $stmt->fetch();
    
    if (!$reminder) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Reminder not found']);
        exit;
    }
    
    // Toggle completed status
    $new_status = !$reminder['completed'];
    $stmt = $pdo->prepare("UPDATE reminders SET completed = ? WHERE id = ?");
    $stmt->execute([$new_status, $reminder_id]);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'completed' => $new_status]);
    exit;
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Failed to update reminder']);
    exit;
}
?>
