<?php
session_start();

// Include Database class
require_once('./database.php');

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['edit_task_id'];
    $new_task_content = $_POST['edit_task_content'];

    // Update task content in the database
    $stmt = $conn->prepare("UPDATE todolist SET task = :task WHERE id = :id");
    $stmt->bindParam(':task', $new_task_content);
    $stmt->bindParam(':id', $task_id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Task updated successfully.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Failed to update task.'
        ];
    }
}

header('Location: ../../src/index.php'); // Redirect back to index.php
exit();
