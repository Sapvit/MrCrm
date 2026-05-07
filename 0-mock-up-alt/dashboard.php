<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user's spaces
try {
    $stmt = $pdo->prepare("SELECT * FROM spaces WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $spaces = $stmt->fetchAll();
} catch (PDOException $e) {
    $spaces = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notes & Reminders</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1>Notes & Reminders</h1>
            <div class="nav-right">
                <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h2>My Spaces</h2>
            <button class="btn btn-primary" onclick="showCreateSpaceModal()">+ Create New Space</button>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="spaces-grid">
            <?php if (empty($spaces)): ?>
                <p class="empty-state">No spaces yet. Create your first space to get started!</p>
            <?php else: ?>
                <?php foreach ($spaces as $space): ?>
                    <div class="space-card">
                        <h3><?php echo htmlspecialchars($space['name']); ?></h3>
                        <p><?php echo htmlspecialchars($space['description']); ?></p>
                        <div class="card-actions">
                            <a href="space.php?id=<?php echo $space['id']; ?>" class="btn btn-primary">Open</a>
                            <a href="delete_space.php?id=<?php echo $space['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this space and all its contents?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Create Space Modal -->
    <div id="createSpaceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCreateSpaceModal()">&times;</span>
            <h2>Create New Space</h2>
            <form action="create_space.php" method="POST">
                <div class="form-group">
                    <label for="space_name">Space Name:</label>
                    <input type="text" id="space_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="space_description">Description:</label>
                    <textarea id="space_description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Space</button>
            </form>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
