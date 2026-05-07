<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !isset($_GET['note_id'])) {
    header('Location: dashboard.php');
    exit;
}

$file_id = $_GET['id'];
$note_id = $_GET['note_id'];
$user_id = $_SESSION['user_id'];

try {
    // Get file info and verify it belongs to user's note
    $stmt = $pdo->prepare("
        SELECT f.* FROM files f 
        JOIN notes n ON f.note_id = n.id 
        JOIN spaces s ON n.space_id = s.id 
        WHERE f.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$file_id, $user_id]);
    $file = $stmt->fetch();
    
    if ($file) {
        // Delete physical file first
        $file_deleted = false;
        if (file_exists($file['filepath'])) {
            $file_deleted = unlink($file['filepath']);
        } else {
            // File doesn't exist, so consider it deleted
            $file_deleted = true;
        }
        
        // Only delete database record if file was successfully deleted
        if ($file_deleted) {
            $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
            $stmt->execute([$file_id]);
            
            header('Location: note.php?id=' . $note_id . '&success=File deleted successfully');
            exit;
        } else {
            header('Location: note.php?id=' . $note_id . '&error=Failed to delete file');
            exit;
        }
    } else {
        header('Location: dashboard.php?error=File not found');
        exit;
    }
} catch (PDOException $e) {
    header('Location: note.php?id=' . $note_id . '&error=Failed to delete file');
    exit;
}
?>
