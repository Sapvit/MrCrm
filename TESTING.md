# Testing Guide

## Prerequisites

1. **Server Requirements:**
   - PHP 7.0 or higher (PDO SQLite enabled)
   - SQLite 3
   - Web server (Apache/Nginx with mod_rewrite enabled)

2. **Installation Steps:**
   - Clone the repository
   - Ensure uploads and data directories are writable: `chmod 777 uploads/ data/`
   - Update the database path in `config.php` if needed
   - Navigate to `setup.php` in your browser to create database tables
   - You should see "Database setup completed successfully!"

## Testing Workflow

### 1. User Registration and Login

**Test Registration:**
1. Navigate to the application homepage (index.php)
2. Click "Register here" link
3. Fill in the form:
   - Username: testuser
   - Email: test@example.com
   - Password: testpass123 (min 8 characters)
   - Confirm Password: testpass123
4. Click "Register"
5. ✓ Should redirect to login page with success message

**Test Login:**
1. On login page, enter:
   - Username: testuser
   - Password: testpass123
2. Click "Login"
3. ✓ Should redirect to dashboard

**Test Invalid Login:**
1. Try logging in with wrong credentials
2. ✓ Should show error message

### 2. Space Management

**Create Space:**
1. On dashboard, click "+ Create New Space"
2. Fill in the form:
   - Space Name: "Work Projects"
   - Description: "My work-related notes"
3. Click "Create Space"
4. ✓ Space should appear on dashboard

**Create Multiple Spaces:**
1. Create 2-3 more spaces with different names
2. ✓ All spaces should be displayed in a grid

**Open Space:**
1. Click "Open" on any space card
2. ✓ Should navigate to space view with three tabs: Notes, Reminders, Files

**Delete Space:**
1. From dashboard, click "Delete" on a space
2. Confirm deletion
3. ✓ Space should be removed from dashboard

### 3. Note Management

**Create Note:**
1. Open a space
2. Click "+ Create Note"
3. Fill in:
   - Title: "Meeting Notes"
   - Content: "Discussed project timeline and deliverables"
4. Click "Create Note"
5. ✓ Note should appear in the notes grid

**View Note:**
1. Click "View" on a note card
2. ✓ Should open detailed note view

**Edit Note:**
1. In note view, click "Edit"
2. Modify the title and/or content
3. Click "Save Changes"
4. ✓ Changes should be reflected

**Delete Note:**
1. In note view, click "Delete"
2. Confirm deletion
3. ✓ Should redirect to space with success message

### 4. Reminder Management

**Add Reminder to Note:**
1. Open a note
2. Click "+ Add Reminder" in the Reminders section
3. Fill in:
   - Title: "Follow up on action items"
   - Due Date: Select a future date/time (optional)
4. Click "Add Reminder"
5. ✓ Reminder should appear in the note's reminder list

**Toggle Reminder Completion:**
1. Check the checkbox next to a reminder
2. ✓ Reminder should get strikethrough and faded appearance (without page reload)
3. Uncheck the checkbox
4. ✓ Reminder should return to normal appearance

**View All Reminders in Space:**
1. Go back to the space view
2. Click the "Reminders" tab
3. ✓ Should see all reminders from all notes in that space
4. ✓ Each reminder should show which note it belongs to

**Delete Reminder:**
1. In note view, click "Delete" on a reminder
2. Confirm deletion
3. ✓ Reminder should be removed

### 5. File Attachment Management

**Upload File:**
1. Open a note
2. Click "+ Upload File" in the Attachments section
3. Select a file (must be one of: jpg, jpeg, png, gif, pdf, doc, docx, txt, zip, rar, xlsx, xls, ppt, pptx)
4. Click "Upload"
5. ✓ File should appear in the attachments list with correct size

**Test File Size Limit:**
1. Try uploading a file larger than 10MB
2. ✓ Should show error: "File is too large (max 10MB)"

**Test Invalid File Type:**
1. Try uploading a file with disallowed extension (e.g., .exe, .sh)
2. ✓ Should show error: "File type not allowed"

**View All Files in Space:**
1. Go back to the space view
2. Click the "Files" tab
3. ✓ Should see all files from all notes in that space
4. ✓ Each file should show which note it belongs to

**Download File:**
1. Click "Download" on a file
2. ✓ File should download to your computer

**Delete File:**
1. In note view, click "Delete" on a file attachment
2. Confirm deletion
3. ✓ File should be removed from list and filesystem

### 6. Navigation and User Experience

**Navigation Flow:**
1. Test navigation: Dashboard → Space → Note → Back to Space → Back to Dashboard
2. ✓ All navigation links should work correctly

**Logout:**
1. Click "Logout" from any page
2. ✓ Should redirect to login page with success message

**Session Security:**
1. After logout, try accessing dashboard.php directly
2. ✓ Should redirect to login page

### 7. Edge Cases

**Empty States:**
1. Create a new space
2. ✓ Should show "No notes yet. Create your first note!" message
3. Create a note without reminders
4. ✓ Should show "No reminders for this note." message

**Long Content:**
1. Create a note with very long title and content
2. ✓ Content should be properly displayed and wrapped

**Special Characters:**
1. Try creating notes/spaces with special characters in names
2. ✓ Should be properly escaped and displayed

**Multiple Users:**
1. Register a second user account
2. Login as second user
3. ✓ Should not see first user's spaces/notes
4. ✓ Each user should have isolated data

## Security Testing

**SQL Injection Prevention:**
- Try entering SQL commands in input fields: `'; DROP TABLE users; --`
- ✓ Should be safely escaped and not execute

**XSS Prevention:**
- Try entering JavaScript: `<script>alert('XSS')</script>`
- ✓ Should be escaped and displayed as text

**Password Security:**
- Check database: passwords should be hashed (not plain text)
- ✓ Should see bcrypt hashes in users table

**File Upload Security:**
- File uploads should have unique names to prevent conflicts
- ✓ Files should be stored with unique identifiers

**Authorization:**
- Try accessing another user's space/note by changing ID in URL
- ✓ Should show "not found" error or redirect

## Browser Compatibility

Test on:
- Chrome/Chromium (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Mobile Responsiveness

Test on various screen sizes:
- Desktop (1920x1080)
- Tablet (768x1024)
- Mobile (375x667)

✓ Layout should adapt appropriately

## Expected Results Summary

All features should work as described above. The application should:
- Handle user authentication securely
- Allow CRUD operations on spaces, notes, reminders, and files
- Show proper relationships between entities
- Provide intuitive navigation
- Display appropriate success/error messages
- Prevent unauthorized access
- Handle edge cases gracefully
