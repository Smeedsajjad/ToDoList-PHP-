 // Function to handle radio button change event
 function handleRadioChange(radio) {
    var editButton = document.getElementById('editButton');
    var deleteButton = document.getElementById('deleteButton');
    var duplicateButton = document.getElementById('duplicateButton');
    var taskContent = radio.closest('.row').querySelector('.task-content').textContent;

    // Enable edit, delete, and duplicate buttons
    editButton.disabled = false;
    deleteButton.disabled = false;
    duplicateButton.disabled = false;

    // Set edit form values
    document.getElementById('edit_id').value = radio.value;
    document.getElementById('editTextarea').value = taskContent;
}

// Function to duplicate the selected task
function duplicateTask() {
    var selectedTask = document.querySelector('input[name="selectedTask"]:checked');
    if (!selectedTask) return;

    var taskRow = selectedTask.closest('.row');
    var taskContent = taskRow.querySelector('.task-content').textContent;

    // Create a new task element by cloning the selected one
    var newTaskRow = taskRow.cloneNode(true);

    // Update the id and value of the new radio button
    var newRadioButton = newTaskRow.querySelector('input[name="selectedTask"]');
    var newTaskId = "task_" + new Date().getTime(); // Use timestamp as unique id
    newRadioButton.id = newTaskId;
    newRadioButton.value = newTaskId;

    // Insert the new task element into the DOM
    var tasksContainer = document.getElementById('tasksContainer');
    tasksContainer.appendChild(newTaskRow);

    // Reattach event listeners to the new radio button
    newRadioButton.addEventListener('change', function() {
        handleRadioChange(newRadioButton);
    });
}

// Event listener for radio button change
var taskRadios = document.querySelectorAll('input[name="selectedTask"]');
taskRadios.forEach(function(radio) {
    radio.addEventListener('change', function() {
        handleRadioChange(radio);
    });
});

// Event listener for duplicate button click
document.getElementById('duplicateButton').addEventListener('click', duplicateTask);

// Event listener for delete button click
document.getElementById('deleteButton').addEventListener('click', function() {
    var confirmDeleteButton = document.getElementById('confirmDeleteButton');
    confirmDeleteButton.onclick = function() {
        var selectedTaskId = document.querySelector('input[name="selectedTask"]:checked').value;
        var selectedTaskRow = document.querySelector(`input[value="${selectedTaskId}"]`).closest('.row');
        selectedTaskRow.remove();
    };
});

// Event listener for edit button click (modal)
document.getElementById('editButton').addEventListener('click', function() {
    var selectedTask = document.querySelector('input[name="selectedTask"]:checked');
    if (selectedTask) {
        var taskId = selectedTask.value;
        var taskContent = selectedTask.closest('.row').querySelector('.task-content').textContent;

        // Set values in the edit form
        document.getElementById('edit_id').value = taskId;
        document.getElementById('editTextarea').value = taskContent;
    }
});
