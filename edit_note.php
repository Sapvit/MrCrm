<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = $_POST['note_id'];
    $space_id = $_POST['space_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($title)) {
        header('Location: note.php?id=' . $note_id . '&error=Note title is required');
        exit;
    }
    
    try {
        // Verify note belongs to user's space
        $stmt = $pdo->prepare("
            SELECT n.id FROM notes n 
            JOIN spaces s ON n.space_id = s.id 
            WHERE n.id = ? AND s.user_id = ?
        ");
        $stmt->execute([$note_id, $user_id]);
        
        if (!$stmt->fetch()) {
            header('Location: dashboard.php?error=Note not found');
            exit;
        }
        
        // Update note
        $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $note_id]);
        
        header('Location: note.php?id=' . $note_id . '&success=Note updated successfully');
        exit;
    } catch (PDOException $e) {
        header('Location: note.php?id=' . $note_id . '&error=Failed to update note');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
