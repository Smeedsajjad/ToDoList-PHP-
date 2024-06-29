<?php
session_start();

// Include Database class
require_once('./database.php');

$database = new Database();
$conn = $database->getConnection();

// Check if ID parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare statement to delete task
    $stmt = $conn->prepare("DELETE FROM todolist WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        // Set success message
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Task deleted successfully.'
        ];
    } else {
        // Set error message
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Failed to delete task. Please try again.'
        ];
    }
} else {
    // Set error message if ID is not provided
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Invalid request. Task ID is missing.'
    ];
}

// Redirect back to index.php
header('Location: ../../src/index.php');
exit();
?>
