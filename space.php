<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$space_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify space belongs to user
try {
    $stmt = $pdo->prepare("SELECT * FROM spaces WHERE id = ? AND user_id = ?");
    $stmt->execute([$space_id, $user_id]);
    $space = $stmt->fetch();
    
    if (!$space) {
        header('Location: dashboard.php?error=Space not found');
        exit;
    }
    
    // Get notes for this space
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE space_id = ? ORDER BY updated_at DESC");
    $stmt->execute([$space_id]);
    $notes = $stmt->fetchAll();
    
    // Get all reminders for this space
    $stmt = $pdo->prepare("
        SELECT r.*, n.title as note_title 
        FROM reminders r 
        JOIN notes n ON r.note_id = n.id 
        WHERE n.space_id = ? 
        ORDER BY r.due_date ASC
    ");
    $stmt->execute([$space_id]);
    $reminders = $stmt->fetchAll();
    
    // Get all files for this space
    $stmt = $pdo->prepare("
        SELECT f.*, n.title as note_title 
        FROM files f 
        JOIN notes n ON f.note_id = n.id 
        WHERE n.space_id = ? 
        ORDER BY f.created_at DESC
    ");
    $stmt->execute([$space_id]);
    $files = $stmt->fetchAll();
    
} catch (PDOException $e) {
    header('Location: dashboard.php?error=Failed to load space');
    exit;
}

$view = isset($_GET['view']) ? $_GET['view'] : 'notes';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($space['name']); ?> - Notes & Reminders</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1>Notes & Reminders</h1>
            <div class="nav-right">
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <div>
                <h2><?php echo htmlspecialchars($space['name']); ?></h2>
                <p><?php echo htmlspecialchars($space['description']); ?></p>
            </div>
            <button class="btn btn-primary" onclick="showCreateNoteModal()">+ Create Note</button>
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
        
        <!-- View Tabs -->
        <div class="tabs">
            <a href="?id=<?php echo $space_id; ?>&view=notes" class="tab <?php echo $view === 'notes' ? 'active' : ''; ?>">
                Notes (<?php echo count($notes); ?>)
            </a>
            <a href="?id=<?php echo $space_id; ?>&view=reminders" class="tab <?php echo $view === 'reminders' ? 'active' : ''; ?>">
                Reminders (<?php echo count($reminders); ?>)
            </a>
            <a href="?id=<?php echo $space_id; ?>&view=files" class="tab <?php echo $view === 'files' ? 'active' : ''; ?>">
                Files (<?php echo count($files); ?>)
            </a>
        </div>
        
        <!-- Notes View -->
        <?php if ($view === 'notes'): ?>
            <div class="notes-grid">
                <?php if (empty($notes)): ?>
                    <p class="empty-state">No notes yet. Create your first note!</p>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note-card">
                            <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                            <div class="note-content">
                                <?php echo nl2br(htmlspecialchars(substr($note['content'], 0, 150))); ?>
                                <?php if (strlen($note['content']) > 150) echo '...'; ?>
                            </div>
                            <div class="card-meta">
                                <small>Updated: <?php echo date('M d, Y', strtotime($note['updated_at'])); ?></small>
                            </div>
                            <div class="card-actions">
                                <a href="note.php?id=<?php echo $note['id']; ?>" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Reminders View -->
        <?php if ($view === 'reminders'): ?>
            <div class="reminders-list">
                <?php if (empty($reminders)): ?>
                    <p class="empty-state">No reminders yet.</p>
                <?php else: ?>
                    <?php foreach ($reminders as $reminder): ?>
                        <div class="reminder-item <?php echo $reminder['completed'] ? 'completed' : ''; ?>">
                            <div class="reminder-checkbox">
                                <input type="checkbox" 
                                       <?php echo $reminder['completed'] ? 'checked' : ''; ?> 
                                       onchange="toggleReminder(<?php echo $reminder['id']; ?>)">
                            </div>
                            <div class="reminder-content">
                                <h4><?php echo htmlspecialchars($reminder['title']); ?></h4>
                                <p class="reminder-meta">
                                    From note: <a href="note.php?id=<?php echo $reminder['note_id']; ?>"><?php echo htmlspecialchars($reminder['note_title']); ?></a>
                                    <?php if ($reminder['due_date']): ?>
                                        | Due: <?php echo date('M d, Y H:i', strtotime($reminder['due_date'])); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Files View -->
        <?php if ($view === 'files'): ?>
            <div class="files-list">
                <?php if (empty($files)): ?>
                    <p class="empty-state">No files yet.</p>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <div class="file-item">
                            <div class="file-icon">📎</div>
                            <div class="file-content">
                                <h4><?php echo htmlspecialchars($file['filename']); ?></h4>
                                <p class="file-meta">
                                    From note: <a href="note.php?id=<?php echo $file['note_id']; ?>"><?php echo htmlspecialchars($file['note_title']); ?></a>
                                    | Size: <?php echo round($file['filesize'] / 1024, 2); ?> KB
                                    | Uploaded: <?php echo date('M d, Y', strtotime($file['created_at'])); ?>
                                </p>
                            </div>
                            <div class="file-actions">
                                <a href="<?php echo htmlspecialchars($file['filepath']); ?>" class="btn btn-primary" download>Download</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Create Note Modal -->
    <div id="createNoteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCreateNoteModal()">&times;</span>
            <h2>Create New Note</h2>
            <form action="create_note.php" method="POST">
                <input type="hidden" name="space_id" value="<?php echo $space_id; ?>">
                <div class="form-group">
                    <label for="note_title">Title:</label>
                    <input type="text" id="note_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="note_content">Content:</label>
                    <textarea id="note_content" name="content" rows="6"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Note</button>
            </form>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
