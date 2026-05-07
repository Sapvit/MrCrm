<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$note_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Get note and verify user owns the space
    $stmt = $pdo->prepare("
        SELECT n.*, s.id as space_id, s.name as space_name, s.user_id 
        FROM notes n 
        JOIN spaces s ON n.space_id = s.id 
        WHERE n.id = ? AND s.user_id = ?
    ");
    $stmt->execute([$note_id, $user_id]);
    $note = $stmt->fetch();
    
    if (!$note) {
        header('Location: dashboard.php?error=Note not found');
        exit;
    }
    
    // Get reminders for this note
    $stmt = $pdo->prepare("SELECT * FROM reminders WHERE note_id = ? ORDER BY due_date ASC");
    $stmt->execute([$note_id]);
    $reminders = $stmt->fetchAll();
    
    // Get files for this note
    $stmt = $pdo->prepare("SELECT * FROM files WHERE note_id = ? ORDER BY created_at DESC");
    $stmt->execute([$note_id]);
    $files = $stmt->fetchAll();
    
} catch (PDOException $e) {
    header('Location: dashboard.php?error=Failed to load note');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($note['title']); ?> - Notes & Reminders</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1>Notes & Reminders</h1>
            <div class="nav-right">
                <a href="space.php?id=<?php echo $note['space_id']; ?>" class="btn btn-secondary">Back to Space</a>
                <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
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
        
        <div class="note-view">
            <div class="note-header">
                <div>
                    <h2><?php echo htmlspecialchars($note['title']); ?></h2>
                    <p class="note-breadcrumb">
                        <a href="space.php?id=<?php echo $note['space_id']; ?>"><?php echo htmlspecialchars($note['space_name']); ?></a> / 
                        <?php echo htmlspecialchars($note['title']); ?>
                    </p>
                </div>
                <div class="note-actions">
                    <button class="btn btn-primary" onclick="showEditNoteModal()">Edit</button>
                    <a href="delete_note.php?id=<?php echo $note_id; ?>&space_id=<?php echo $note['space_id']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                </div>
            </div>
            
            <div class="note-content-view">
                <?php echo nl2br(htmlspecialchars($note['content'])); ?>
            </div>
            
            <div class="note-meta">
                <small>Created: <?php echo date('M d, Y H:i', strtotime($note['created_at'])); ?></small>
                <small>Last updated: <?php echo date('M d, Y H:i', strtotime($note['updated_at'])); ?></small>
            </div>
        </div>
        
        <!-- Reminders Section -->
        <div class="note-section">
            <div class="section-header">
                <h3>Reminders</h3>
                <button class="btn btn-primary" onclick="showAddReminderModal()">+ Add Reminder</button>
            </div>
            <div class="reminders-list">
                <?php if (empty($reminders)): ?>
                    <p class="empty-state">No reminders for this note.</p>
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
                                <?php if ($reminder['due_date']): ?>
                                    <p class="reminder-meta">Due: <?php echo date('M d, Y H:i', strtotime($reminder['due_date'])); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="reminder-actions">
                                <a href="delete_reminder.php?id=<?php echo $reminder['id']; ?>&note_id=<?php echo $note_id; ?>" 
                                   class="btn btn-danger btn-small" 
                                   onclick="return confirm('Delete this reminder?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Files Section -->
        <div class="note-section">
            <div class="section-header">
                <h3>Attachments</h3>
                <button class="btn btn-primary" onclick="showUploadFileModal()">+ Upload File</button>
            </div>
            <div class="files-list">
                <?php if (empty($files)): ?>
                    <p class="empty-state">No files attached to this note.</p>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <div class="file-item">
                            <div class="file-icon">📎</div>
                            <div class="file-content">
                                <h4><?php echo htmlspecialchars($file['filename']); ?></h4>
                                <p class="file-meta">
                                    Size: <?php echo round($file['filesize'] / 1024, 2); ?> KB | 
                                    Uploaded: <?php echo date('M d, Y', strtotime($file['created_at'])); ?>
                                </p>
                            </div>
                            <div class="file-actions">
                                <a href="<?php echo htmlspecialchars($file['filepath']); ?>" class="btn btn-primary" download>Download</a>
                                <a href="delete_file.php?id=<?php echo $file['id']; ?>&note_id=<?php echo $note_id; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Delete this file?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Edit Note Modal -->
    <div id="editNoteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditNoteModal()">&times;</span>
            <h2>Edit Note</h2>
            <form action="edit_note.php" method="POST">
                <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
                <input type="hidden" name="space_id" value="<?php echo $note['space_id']; ?>">
                <div class="form-group">
                    <label for="edit_title">Title:</label>
                    <input type="text" id="edit_title" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_content">Content:</label>
                    <textarea id="edit_content" name="content" rows="10"><?php echo htmlspecialchars($note['content']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
    
    <!-- Add Reminder Modal -->
    <div id="addReminderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddReminderModal()">&times;</span>
            <h2>Add Reminder</h2>
            <form action="create_reminder.php" method="POST">
                <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
                <div class="form-group">
                    <label for="reminder_title">Title:</label>
                    <input type="text" id="reminder_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date (optional):</label>
                    <input type="datetime-local" id="due_date" name="due_date">
                </div>
                <button type="submit" class="btn btn-primary">Add Reminder</button>
            </form>
        </div>
    </div>
    
    <!-- Upload File Modal -->
    <div id="uploadFileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUploadFileModal()">&times;</span>
            <h2>Upload File</h2>
            <form action="upload_file.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
                <div class="form-group">
                    <label for="file">Choose File:</label>
                    <input type="file" id="file" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
