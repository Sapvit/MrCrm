<?php
// Database configuration
// NOTE: These are development defaults. In production, use environment variables
// and store the database outside the web root if possible.
define('DB_PATH', getenv('DB_PATH') ?: __DIR__ . '/data/mcrm.sqlite');

// Start session
session_start();

// Database connection
try {
    $dbDir = dirname(DB_PATH);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0775, true);
    }

    $pdo = new PDO(
        "sqlite:" . DB_PATH,
        null,
        null,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    $pdo->exec("PRAGMA foreign_keys = ON");
    
    // Auto-initialize database tables if they don't exist
    initializeDatabase($pdo);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize database schema
function initializeDatabase($pdo) {
    try {
        // Check if users table exists
        $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if ($result->fetch()) {
            return; // Tables already exist
        }
        
        // Create users table
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Create spaces table
        $pdo->exec("CREATE TABLE spaces (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            description TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
        
        // Create notes table
        $pdo->exec("CREATE TABLE notes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            space_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            content TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (space_id) REFERENCES spaces(id) ON DELETE CASCADE
        )");
        
        // Create trigger for updated_at
        $pdo->exec("CREATE TRIGGER notes_updated_at
            AFTER UPDATE ON notes
            FOR EACH ROW
            BEGIN
                UPDATE notes SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
            END;
        ");
        
        // Create reminders table
        $pdo->exec("CREATE TABLE reminders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            note_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            due_date TEXT,
            completed INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
        )");
        
        // Create files table
        $pdo->exec("CREATE TABLE files (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            note_id INTEGER NOT NULL,
            filename TEXT NOT NULL,
            filepath TEXT NOT NULL,
            filesize INTEGER,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
        )");
        
    } catch (PDOException $e) {
        // Silently fail - tables may already exist or other connections may be initializing
    }
}
?>
