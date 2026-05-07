<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !isset($_GET['space_id'])) {
    header('Location: dashboard.php');
    exit;
}

$note_id = $_GET['id'];
$space_id = $_GET['space_id'];
$user_id = $_SESSION['user_id'];

try {
    // Verify note belongs to user's space
    $stmt = $pdo->prepare("
        SELECT n.id FROM notes n 
        JOIN spaces s ON n.space_id = s.id 
        WHERE n.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$note_id, $user_id]);
    
    if ($stmt->fetch()) {
        // Delete note (cascade will delete reminders and files)
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->execute([$note_id]);
        
        header('Location: space.php?id=' . $space_id . '&success=Note deleted successfully');
        exit;
    } else {
        header('Location: dashboard.php?error=Note not found');
        exit;
    }
} catch (PDOException $e) {
    header('Location: space.php?id=' . $space_id . '&error=Failed to delete note');
    exit;
}
?>
