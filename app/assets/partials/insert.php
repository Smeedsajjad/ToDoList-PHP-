<?php

include_once 'Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $stmt = $conn->prepare("INSERT INTO todolist (task) VALUES (:task)");

    $task = htmlspecialchars(strip_tags($_POST['task']));
    $stmt->bindParam(':task', $task);

    try {
        $stmt->execute();
        echo "Task added successfully.";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
