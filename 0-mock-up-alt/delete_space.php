<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$space_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Verify space belongs to user
    $stmt = $pdo->prepare("SELECT id FROM spaces WHERE id = ? AND user_id = ?");
    $stmt->execute([$space_id, $user_id]);
    
    if ($stmt->fetch()) {
        // Delete space (cascade will delete notes, reminders, and files)
        $stmt = $pdo->prepare("DELETE FROM spaces WHERE id = ?");
        $stmt->execute([$space_id]);
        
        header('Location: dashboard.php?success=Space deleted successfully');
        exit;
    } else {
        header('Location: dashboard.php?error=Space not found');
        exit;
    }
} catch (PDOException $e) {
    header('Location: dashboard.php?error=Failed to delete space');
    exit;
}
?>
