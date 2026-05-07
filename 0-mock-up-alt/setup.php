<?php
// Database setup script (SQLite)
require_once 'config.php';

try {
    $pdo->exec("PRAGMA foreign_keys = ON");

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");

    // Create spaces table
    $pdo->exec("CREATE TABLE IF NOT EXISTS spaces (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        description TEXT,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create notes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS notes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        space_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        content TEXT,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (space_id) REFERENCES spaces(id) ON DELETE CASCADE
    )");

    // Keep updated_at in sync on edits
    $pdo->exec("CREATE TRIGGER IF NOT EXISTS notes_updated_at
        AFTER UPDATE ON notes
        FOR EACH ROW
        BEGIN
            UPDATE notes SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ");

    // Create reminders table
    $pdo->exec("CREATE TABLE IF NOT EXISTS reminders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        note_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        due_date TEXT,
        completed INTEGER DEFAULT 0,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
    )");

    // Create files table
    $pdo->exec("CREATE TABLE IF NOT EXISTS files (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        note_id INTEGER NOT NULL,
        filename TEXT NOT NULL,
        filepath TEXT NOT NULL,
        filesize INTEGER,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
    )");

    echo "Database setup completed successfully!";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
