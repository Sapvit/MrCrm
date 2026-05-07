# Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Step 1: Setup Database
```bash
# Navigate to setup page in your browser
http://localhost/mcrm/setup.php
```
You should see: "Database setup completed successfully!"

### Step 2: Create Account
```bash
# Go to the application
http://localhost/mcrm/
```
1. Click "Register here"
2. Fill in:
   - Username: your_username
   - Email: your@email.com
   - Password: yourpassword (min 8 chars)
3. Click "Register"

### Step 3: Login
1. Enter your username and password
2. Click "Login"

### Step 4: Create Your First Space
1. Click "+ Create New Space"
2. Enter:
   - Space Name: "Personal Projects"
   - Description: "My personal project notes"
3. Click "Create Space"

### Step 5: Create Your First Note
1. Click "Open" on your space
2. Click "+ Create Note"
3. Enter:
   - Title: "Project Ideas"
   - Content: "List of cool project ideas..."
4. Click "Create Note"

### Step 6: Add a Reminder
1. Click "View" on your note
2. Scroll to Reminders section
3. Click "+ Add Reminder"
4. Enter:
   - Title: "Review project status"
   - Due Date: (optional) Select date/time
5. Click "Add Reminder"

### Step 7: Upload a File
1. In the same note view
2. Scroll to Attachments section
3. Click "+ Upload File"
4. Select a file (jpg, png, pdf, doc, txt, etc.)
5. Click "Upload"

### Step 8: View Aggregated Data
1. Go back to your space
2. Click "Reminders" tab → See all reminders from all notes
3. Click "Files" tab → See all files from all notes
4. Each item shows which note it belongs to

## 📖 Key Features

### Spaces
- Organize notes by project/category
- Create unlimited spaces
- Each space is independent

### Notes
- Simple text notes with titles
- Edit and delete anytime
- Support line breaks

### Reminders
- Add to any note
- Optional due dates
- Check off when complete
- View all in one place

### Files
- Attach to any note
- Max 10MB per file
- Allowed types: jpg, png, pdf, doc, txt, zip, etc.
- Download anytime

## 🎯 Common Tasks

### Create Something New
- **New Space**: Dashboard → "+ Create New Space"
- **New Note**: Space View → "+ Create Note"
- **New Reminder**: Note View → "+ Add Reminder"
- **Upload File**: Note View → "+ Upload File"

### View Things
- **All Spaces**: Dashboard (after login)
- **All Notes**: Space View → Notes tab
- **All Reminders**: Space View → Reminders tab
- **All Files**: Space View → Files tab

### Edit/Delete
- **Edit Note**: Note View → "Edit" button
- **Delete Note**: Note View → "Delete" button
- **Delete Space**: Dashboard → "Delete" button on space
- **Toggle Reminder**: Check/uncheck checkbox
- **Delete File**: Note View → "Delete" on file

## 📱 Navigation

```
Login → Dashboard → Space → Note
         ↑           ↑       ↑
         └───────────┴───────┘
      (Back buttons available)
```

## 💡 Tips

1. **Organize by Project**: Create one space per project
2. **Use Reminders**: Add due dates to stay on track
3. **Attach Files**: Keep related documents with notes
4. **Quick Overview**: Use space tabs to see everything at once
5. **Mark Complete**: Check off reminders when done

## ⚠️ Important Notes

- Minimum password length: 8 characters
- Max file size: 10MB
- Deleting a space deletes all its notes, reminders, and files
- Deleting a note deletes its reminders and files
- Files are stored securely with unique names

## 🔒 Security

- Passwords are hashed (not stored as plain text)
- Each user sees only their own data
- File uploads are validated
- All database queries are secure

## 📞 Need Help?

Check the documentation:
- **README.md** - Overview and features
- **STRUCTURE.md** - How the app is built
- **TESTING.md** - Comprehensive testing guide
- **INSTALL.md** - Deployment instructions
- **SUMMARY.md** - Complete project summary

## 🎉 You're All Set!

Start organizing your notes, setting reminders, and attaching files. Enjoy!
