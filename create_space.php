<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($name)) {
        header('Location: dashboard.php?error=Space name is required');
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO spaces (user_id, name, description) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $name, $description]);
        
        header('Location: dashboard.php?success=Space created successfully');
        exit;
    } catch (PDOException $e) {
        header('Location: dashboard.php?error=Failed to create space');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
