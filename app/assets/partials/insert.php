<?php
session_start(); // Start session

include_once 'Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $stmt = $conn->prepare("INSERT INTO todolist (task) VALUES (:task)");

    $task = htmlspecialchars(strip_tags($_POST['task']));
    $stmt->bindParam(':task', $task);

    try {
        $stmt->execute();
        
        // Set success message in session
        $_SESSION['alert'] = array(
            'type' => 'success',
            'message' => 'Task added successfully!'
        );

        // Redirect to index.php
        header('Location: ../../src/index.php');
        exit;
    } catch(PDOException $e) {
        // Set error message in session if insertion fails
        $_SESSION['alert'] = array(
            'type' => 'danger',
            'message' => 'Error: ' . $e->getMessage()
        );

        // Redirect to index.php
        header('Location: ../../src/index.php');
        exit;
    }
}