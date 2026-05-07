// Modal functions for creating spaces
function showCreateSpaceModal() {
    document.getElementById('createSpaceModal').style.display = 'block';
}

function closeCreateSpaceModal() {
    document.getElementById('createSpaceModal').style.display = 'none';
}

// Modal functions for creating notes
function showCreateNoteModal() {
    document.getElementById('createNoteModal').style.display = 'block';
}

function closeCreateNoteModal() {
    document.getElementById('createNoteModal').style.display = 'none';
}

// Modal functions for editing notes
function showEditNoteModal() {
    document.getElementById('editNoteModal').style.display = 'block';
}

function closeEditNoteModal() {
    document.getElementById('editNoteModal').style.display = 'none';
}

// Modal functions for adding reminders
function showAddReminderModal() {
    document.getElementById('addReminderModal').style.display = 'block';
}

function closeAddReminderModal() {
    document.getElementById('addReminderModal').style.display = 'none';
}

// Modal functions for uploading files
function showUploadFileModal() {
    document.getElementById('uploadFileModal').style.display = 'block';
}

function closeUploadFileModal() {
    document.getElementById('uploadFileModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

// Toggle reminder completion
function toggleReminder(reminderId) {
    const checkbox = event.target;
    const reminderItem = checkbox.closest('.reminder-item');
    
    fetch('toggle_reminder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reminder_id: reminderId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI without reloading
            if (data.completed) {
                reminderItem.classList.add('completed');
                checkbox.checked = true;
            } else {
                reminderItem.classList.remove('completed');
                checkbox.checked = false;
            }
        } else {
            // Revert checkbox on error
            checkbox.checked = !checkbox.checked;
            alert('Failed to update reminder: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        // Revert checkbox on error
        checkbox.checked = !checkbox.checked;
        console.error('Error:', error);
        alert('Failed to update reminder');
    });
}
