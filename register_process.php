<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header('Location: register.php?error=Please fill in all fields');
        exit;
    }
    
    if (strlen($username) < 3) {
        header('Location: register.php?error=Username must be at least 3 characters');
        exit;
    }
    
    if (strlen($password) < 8) {
        header('Location: register.php?error=Password must be at least 8 characters');
        exit;
    }
    
    if ($password !== $confirm_password) {
        header('Location: register.php?error=Passwords do not match');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=Invalid email address');
        exit;
    }
    
    try {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            header('Location: register.php?error=Username already exists');
            exit;
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header('Location: register.php?error=Email already exists');
            exit;
        }
        
        // Hash password and insert user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        
        header('Location: index.php?success=Registration successful! Please login.');
        exit;
        
    } catch (PDOException $e) {
        header('Location: register.php?error=Registration failed. Please try again.');
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}
?>
