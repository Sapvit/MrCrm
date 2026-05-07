<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !isset($_GET['note_id'])) {
    header('Location: dashboard.php');
    exit;
}

$reminder_id = $_GET['id'];
$note_id = $_GET['note_id'];
$user_id = $_SESSION['user_id'];

try {
    // Verify reminder belongs to user's note
    $stmt = $pdo->prepare("
        SELECT r.id FROM reminders r 
        JOIN notes n ON r.note_id = n.id 
        JOIN spaces s ON n.space_id = s.id 
        WHERE r.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$reminder_id, $user_id]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("DELETE FROM reminders WHERE id = ?");
        $stmt->execute([$reminder_id]);
        
        header('Location: note.php?id=' . $note_id . '&success=Reminder deleted successfully');
        exit;
    } else {
        header('Location: dashboard.php?error=Reminder not found');
        exit;
    }
} catch (PDOException $e) {
    header('Location: note.php?id=' . $note_id . '&error=Failed to delete reminder');
    exit;
}
?>
