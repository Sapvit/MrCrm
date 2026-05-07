# Application Structure

## File Organization

### Core Files
- **index.php** - Landing page with login form
- **register.php** - User registration page
- **dashboard.php** - Main dashboard showing user's spaces
- **space.php** - Individual space view with notes, reminders, and files tabs
- **note.php** - Detailed note view with reminders and attachments

### Configuration
- **config.php** - Database configuration and connection
- **setup.php** - Database initialization script (run once)

### Authentication Handlers
- **login_process.php** - Handles user login
- **register_process.php** - Handles user registration
- **logout.php** - Handles user logout

### Space Management
- **create_space.php** - Creates new space
- **delete_space.php** - Deletes a space and all its contents

### Note Management
- **create_note.php** - Creates new note in a space
- **edit_note.php** - Updates existing note
- **delete_note.php** - Deletes a note and all its contents

### Reminder Management
- **create_reminder.php** - Creates new reminder for a note
- **toggle_reminder.php** - Toggles reminder completion status (AJAX)
- **delete_reminder.php** - Deletes a reminder

### File Management
- **upload_file.php** - Handles file uploads
- **delete_file.php** - Deletes uploaded file

### Assets
- **assets/css/style.css** - All application styling
- **assets/js/script.js** - JavaScript for modals and interactive features

### Directories
- **uploads/** - Storage for uploaded files
- **includes/** - Reserved for future shared components
- **assets/** - Static assets (CSS, JS, images)

## Database Schema

### users
- id (PK)
- username (unique)
- email (unique)
- password (hashed)
- created_at

### spaces
- id (PK)
- user_id (FK -> users)
- name
- description
- created_at

### notes
- id (PK)
- space_id (FK -> spaces)
- title
- content
- created_at
- updated_at

### reminders
- id (PK)
- note_id (FK -> notes)
- title
- due_date
- completed (boolean)
- created_at

### files
- id (PK)
- note_id (FK -> notes)
- filename
- filepath
- filesize
- created_at

## Application Flow

1. User visits index.php (login page)
2. User registers via register.php or logs in
3. After login, redirected to dashboard.php
4. User creates spaces from dashboard
5. User clicks on space to view space.php
6. In space view, user can:
   - Create notes
   - View all reminders (from all notes in space)
   - View all files (from all notes in space)
7. User clicks on note to view note.php
8. In note view, user can:
   - Edit note content
   - Add/delete reminders
   - Upload/delete files
   - Mark reminders as complete

## Security Features

- Password hashing with password_hash()
- Prepared statements for SQL queries
- Session-based authentication
- User ownership verification for all operations
- File upload validation

## Future Enhancements

- Rich text editor (WYSIWYG) for notes
- Search functionality
- Tags for notes
- Sharing spaces with other users
- Email notifications for reminders
- Mobile responsive improvements
- Dark mode
