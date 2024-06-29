<?php
session_start(); // Start session

// Include Database class
require_once('./database.php');

$database = new Database();
$conn = $database->getConnection();

// Get the search term from the query string
$search_term = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($search_term !== '') {
    // Prepare the SQL statement to search for tasks
    $stmt = $conn->prepare("SELECT id, task FROM todolist WHERE task LIKE :search_term");
    $search_term_with_wildcards = '%' . $search_term . '%';
    $stmt->bindParam(':search_term', $search_term_with_wildcards, PDO::PARAM_STR);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If no search term is provided, fetch all tasks
    $stmt = $conn->query("SELECT id, task FROM todolist");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Return the tasks as JSON
header('Content-Type: application/json');
echo json_encode($tasks);
?>
