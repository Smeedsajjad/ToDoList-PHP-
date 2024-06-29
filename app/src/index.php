<?php
// Include your Database class file
require_once ('../assets/partials/database.php');

$database = new Database();
// Display any success or error messages from insert.php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
  include_once 'insert.php';
}
// Now you can use $database->connection to execute queries
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    @media screen and (min-width: 575px) {
      .smx {
        width: 80% !important;
      }
    }
  </style>
</head>

<body>
  <!-- Modal ADD -->
  <form action="../assets/partials/insert.php" method="post">
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="addModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="addModalLabel">Add List</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <input type="hidden" name="id">
          <div class="modal-body">
            <textarea name="task" class="form-control" id="addTextarea" rows="3"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button style="background-color: #c7bea2;" class="btn btn-primary" type="submit">Add</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- Modal -->

  <!-- Modal EDIT -->
  <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit List</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <textarea class="form-control" id="editTextarea" rows="3"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button style="background-color: #c7bea2;" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->

  <!-- Modal DELETE -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="deleteModalLabel">Delete List</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this list?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->

  <header>
    <div class="container-fluid">
      <div id="header">
        <h1>Have a good day!</h1>
      </div>
    </div>
  </header>

  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <!-- Button trigger modals -->
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal"
            id="addButton">
            Add List
          </button>

        </div>
        <div class="col-md-4 form-group has-search">
          <span class="fa fa-search form-control-feedback"></span>
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <div class="col-md-4">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editModal" disabled
            id="editButton">
            Edit List
          </button>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" disabled
            id="deleteButton">
            Delete
          </button>
        </div>
      </div>

      <!-- Display tasks dynamically -->
      <div class="row mt-5" id="taskList">
        <!-- Tasks will be dynamically added here -->
      </div>
    </div>
  </section>

  <script src="./../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/script.js"></script>
  <script>
    // Function to fetch tasks and update UI
    function fetchTasks() {
      fetch('../assets/partials/fetch_tasks.php')
        .then(response => response.json())
        .then(data => {
          const taskList = document.getElementById('taskList');
          taskList.innerHTML = ''; // Clear existing content

          // Loop through tasks and create HTML for each task
          data.forEach(task => {
            const taskHTML = `
              <div class="col-lg-1 col-md-1 col-sm-1 smx2"
                style="display: flex; align-items: center; padding: 15px 0 0 0; justify-content: center;">
                <input class="form-check-input p-2" type="radio" name="checkboxNoLabel" id="checkboxNoLabel"
                  aria-label="...">
              </div>
              <div class="col-lg-11 col-md-11 col-sm-11 smx">
                <div>
                  <p class="task">
                    <span class="pe-3">${task.id}.</span>
                    <span>${task.task}</span>
                  </p>
                </div>
              </div>
            `;
            taskList.innerHTML += taskHTML; // Append task HTML to taskList
          });
        })
        .catch(error => console.error('Error fetching tasks:', error));
    }

    // Call fetchTasks when the page loads
    document.addEventListener('DOMContentLoaded', fetchTasks);
  </script>
</body>

</html>
