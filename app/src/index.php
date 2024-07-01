<?php
session_start(); // Start session

// Include Database class
require_once ('../assets/partials/Database.php');

$database = new Database();
$conn = $database->getConnection();

// Check if alert message is set
$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;

// Clear the alert message from session once used
unset($_SESSION['alert']);

// Pagination variables
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$items_per_page = 5;
$offset = ($page - 1) * $items_per_page;

// Fetch tasks from the database with pagination
$stmt = $conn->prepare("SELECT id, task FROM todolist LIMIT :offset, :items_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total tasks for pagination
$total_stmt = $conn->query("SELECT COUNT(*) FROM todolist");
$total_tasks = $total_stmt->fetchColumn();
$total_pages = ceil($total_tasks / $items_per_page);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToDoList</title>
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

    nav {
      justify-content: center;
      display: flex;
    }

    .active>.page-link,
    .page-link.active {
      z-index: 3;
      color: var(--bs-pagination-active-color);
      background-color: #aaa694;
      border: none;
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
        <form action="../assets/partials/update.php" method="post" id="editForm">
          <input type="hidden" id="edit_id" name="edit_task_id">
          <div class="modal-body">
            <textarea class="form-control" id="editTextarea" name="edit_task_content" rows="3"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button style="background-color: #c7bea2;" type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
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
          <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
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
          <input type="text" class="form-control" placeholder="Search" id="searchInput">
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
          <button type="button" class="btn btn-secondary" id="duplicateButton" disabled>Duplicate</button>
        </div>
      </div>

      <!-- Display Bootstrap alert if alert message is set -->
      <?php if ($alert): ?>
        <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show mt-3" role="alert">
          <?php echo $alert['message']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Display Data from database -->
      <div id="tasksContainer">
        <?php foreach ($tasks as $task): ?>
          <div class="row mt-5">
            <div class="col-lg-1 col-md-1 col-sm-1 smx2"
              style="display: flex; align-items: center; justify-content: center;">
              <input class="form-check-input p-2" type="radio" name="selectedTask" id="task_<?php echo $task['id']; ?>"
                value="<?php echo $task['id']; ?>" aria-label="...">
            </div>
            <div class="col-lg-11 col-md-11 col-sm-11 smx">
              <div>
                <p class="task">
                  <span class="task-id"><?php echo $task['id']; ?></span>
                  <span class="task-content"><?php echo $task['task']; ?></span>
                </p>
              </div>
            </div>
          </div>

          <!-- Delete Confirmation Modal -->
          <div class="modal fade" id="deleteModal_<?php echo $task['id']; ?>" tabindex="-1"
            aria-labelledby="deleteModalLabel_<?php echo $task['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalLabel_<?php echo $task['id']; ?>">Delete Confirmation</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete this task?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <a href="../assets/partials/delete.php?id=<?php echo $task['id']; ?>" class="btn btn-danger">Delete</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <div class="row mt-4">
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1)
              echo 'disabled'; ?>">
              <a class="page-link" href="<?php if ($page > 1)
                echo '?page=' . ($page - 1);
              else
                echo '#'; ?>"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php if ($page == $i)
                echo 'active'; ?>"><a class="page-link"
                  href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?php if ($page >= $total_pages)
              echo 'disabled'; ?>">
              <a class="page-link" href="<?php if ($page < $total_pages)
                echo '?page=' . ($page + 1);
              else
                echo '#'; ?>"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </section>
  <script src="./../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>

</html>