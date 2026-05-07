<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = $_POST['note_id'];
    $title = trim($_POST['title']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $user_id = $_SESSION['user_id'];
    
    if (empty($title)) {
        header('Location: note.php?id=' . $note_id . '&error=Reminder title is required');
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
        
        // Create reminder
        $stmt = $pdo->prepare("INSERT INTO reminders (note_id, title, due_date) VALUES (?, ?, ?)");
        $stmt->execute([$note_id, $title, $due_date]);
        
        header('Location: note.php?id=' . $note_id . '&success=Reminder added successfully');
        exit;
    } catch (PDOException $e) {
        header('Location: note.php?id=' . $note_id . '&error=Failed to create reminder');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
