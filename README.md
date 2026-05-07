# MrCrm - Notes & Reminders Management System

A PHP-based application for managing notes, reminders, and files with support for multiple interface designs.

## 📋 Project Overview

MrCrm is a flexible note-taking and reminder system with two different user interface implementations:

- **Main Interface** (root directory) - The primary production-ready interface
- **Alternative Mock-up** (`0-mock-up-alt/`) - An alternative UI design for testing and comparison

Both interfaces share the same core functionality and can work with the same database.

## ✨ Features

- 👤 **User Authentication**: Secure login and registration system
- 📁 **Spaces/Projects**: Organize notes into separate spaces or projects
- 📝 **Notes**: Create, edit, and manage notes with text formatting
- ⏰ **Reminders**: Set reminders with due dates and completion tracking
- 📎 **File Attachments**: Upload and attach files to notes
- 🏷️ **Organized Views**: Browse all notes, reminders, and files with organized tags

## 🗂️ Project Structure

```
├── index.php                 # Main entry point
├── home.php                  # Dashboard/home page
├── project.php               # Project management
├── config.php                # Application configuration
├── assets/                   # CSS and JavaScript files
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript files
├── components/               # Reusable PHP components
│   ├── left_sidebar.php
│   ├── right_sidebar.php
│   └── top_bar.php
├── views/                    # View templates
│   ├── note.php
│   ├── notes_list.php
│   ├── files_list.php
│   ├── reminders_list.php
│   └── project_home.php
├── 0-mock-up-alt/           # Alternative UI design (separate interface)
│   ├── README.md            # Documentation for alternative design
│   └── ...                  # Alternative interface files
└── 0-bak/                   # Backup directory

```

## 🚀 Quick Start

### Prerequisites

- PHP 7.0 or higher (PDO SQLite enabled by default)
- Web server (Apache/Nginx)
- Browser with JavaScript enabled

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Sapvit/MrCrm.git
   cd MrCrm
   ```

2. **Set up file permissions**:
   ```bash
   chmod 777 uploads/
   chmod 777 data/  # if it exists
   ```

3. **Access the application**:
   - Navigate to `http://localhost/MrCrm/` in your browser
   - Database will be created automatically on first access
   - Register a new account to get started

   **That's it!** No manual database setup required. 🎉

## 💻 Using Different Interfaces

### Main Interface
Access the primary interface at the root directory:
```
http://localhost/MrCrm/
```

### Alternative Mock-up
To test the alternative interface design:
```
http://localhost/MrCrm/0-mock-up-alt/
```

Both interfaces use the same underlying database, so your data will be available across both designs.

See [0-mock-up-alt/README.md](0-mock-up-alt/README.md) for more details about the alternative interface.

## 📚 Usage Guide

1. **Login/Register**: Create an account or login with existing credentials
2. **Create a Space**: From the dashboard, create a new project or space
3. **Add Notes**: Within a space, create notes with formatting
4. **Set Reminders**: Open a note and add reminders with due dates
5. **Upload Files**: Attach files to notes for reference
6. **View & Organize**: Use tabs to view all notes, reminders, or files

## 🗄️ Database

- **Type**: SQLite 3
- **Location**: `data/mcrm.sqlite` (created automatically on first access)
- **Setup**: Automatic - no manual database setup needed

## 🛠️ Configuration

Edit `config.php` to:
- Configure database connection (if using MySQL instead of SQLite)
- Adjust HTML sanitization rules
- Set application-level settings

## 📂 File Organization

| Directory | Purpose |
|-----------|---------|
| `assets/` | CSS stylesheets and JavaScript files |
| `components/` | Reusable PHP UI components |
| `views/` | Page templates and view files |
| `0-mock-up-alt/` | Alternative interface design |
| `0-bak/` | Backup/archive files |

## 🔒 Security

- HTML input sanitization to prevent XSS attacks
- Session-based user authentication
- Configurable allowed HTML tags in notes

## 📝 Notes

- The application uses PHP sessions for user management
- File uploads are stored in the `uploads/` directory
- Both interfaces can be used simultaneously with the same database

## 🤝 Contributing

Feel free to fork, modify, and improve this project!

## 📄 License

[Add your license here if applicable]

---

**For more details about the alternative interface design, see [0-mock-up-alt/README.md](0-mock-up-alt/README.md)**
