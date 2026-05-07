<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        header('Location: index.php?error=Please fill in all fields');
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            header('Location: index.php?error=Invalid username or password');
            exit;
        }
    } catch (PDOException $e) {
        header('Location: index.php?error=Login failed. Please try again.');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>
