<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = $_POST['note_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify note belongs to user's space
    try {
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
        
        // Check if file was uploaded
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            header('Location: note.php?id=' . $note_id . '&error=Failed to upload file');
            exit;
        }
        
        $file = $_FILES['file'];
        $filename = basename($file['name']);
        $filesize = $file['size'];
        
        // Validate file size (max 10MB)
        $max_size = 10 * 1024 * 1024; // 10MB
        if ($filesize > $max_size) {
            header('Location: note.php?id=' . $note_id . '&error=File is too large (max 10MB)');
            exit;
        }
        
        // Validate file type
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'xlsx', 'xls', 'ppt', 'pptx'];
        if (!in_array($ext, $allowed_extensions)) {
            header('Location: note.php?id=' . $note_id . '&error=File type not allowed');
            exit;
        }
        
        // Generate unique filename
        $unique_filename = uniqid() . '_' . time() . '.' . $ext;
        $filepath = 'uploads/' . $unique_filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            header('Location: note.php?id=' . $note_id . '&error=Failed to save file');
            exit;
        }
        
        // Save file info to database
        $stmt = $pdo->prepare("INSERT INTO files (note_id, filename, filepath, filesize) VALUES (?, ?, ?, ?)");
        $stmt->execute([$note_id, $filename, $filepath, $filesize]);
        
        header('Location: note.php?id=' . $note_id . '&success=File uploaded successfully');
        exit;
        
    } catch (PDOException $e) {
        header('Location: note.php?id=' . $note_id . '&error=Failed to upload file');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
