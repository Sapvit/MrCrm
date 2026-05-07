<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $space_id = $_POST['space_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($title)) {
        header('Location: space.php?id=' . $space_id . '&error=Note title is required');
        exit;
    }
    
    try {
        // Verify space belongs to user
        $stmt = $pdo->prepare("SELECT id FROM spaces WHERE id = ? AND user_id = ?");
        $stmt->execute([$space_id, $user_id]);
        
        if (!$stmt->fetch()) {
            header('Location: dashboard.php?error=Space not found');
            exit;
        }
        
        // Create note
        $stmt = $pdo->prepare("INSERT INTO notes (space_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$space_id, $title, $content]);
        
        header('Location: space.php?id=' . $space_id . '&success=Note created successfully');
        exit;
    } catch (PDOException $e) {
        header('Location: space.php?id=' . $space_id . '&error=Failed to create note');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
