<?php
// Direct database initialization script
$dbPath = __DIR__ . '/data/mcrm.sqlite';

// Remove old database if exists
if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Removed old database file.\n";
}

// Create connection
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Created SQLite database at: " . $dbPath . "\n\n";
    
    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON");
    echo "✓ Enabled foreign keys\n";

    // Create users table
    $pdo->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ Created users table\n";
    
    // Create spaces table
    $pdo->exec("CREATE TABLE spaces (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        description TEXT,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "✓ Created spaces table\n";

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
    echo "✓ Created notes table\n";

    // Create trigger for updated_at
    $pdo->exec("CREATE TRIGGER notes_updated_at
        AFTER UPDATE ON notes
        FOR EACH ROW
        BEGIN
            UPDATE notes SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ");
    echo "✓ Created notes_updated_at trigger\n";

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
    echo "✓ Created reminders table\n";

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
    echo "✓ Created files table\n";

    echo "\n✅ Database setup completed successfully!\n";
    echo "Database file: " . $dbPath . "\n";
    echo "File size: " . filesize($dbPath) . " bytes\n";
    
} catch (Exception $e) {
    die("❌ Setup failed: " . $e->getMessage() . "\n");
}
?>
