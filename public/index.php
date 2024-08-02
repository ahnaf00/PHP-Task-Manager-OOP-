<?php

include_once "../config/config.php";
include_once "../classes/TaskManager.php";

$taskManager = new TaskManager();
$pendingTasks = $taskManager->getPendingTasks();
$completedTasks = $taskManager->getCompletedTasks();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mb-4 mt-3">
        <div class="row">
            <h1>Task Manager</h1>
        </div>
    </div>

    <!-- Complete Task Starts -->
    <?php if ($completedTasks->num_rows > 0): ?>
        <div class="container text-center mt-4">
            <div class="row">
                <h5 class="mb-4">Completed Tasks</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">ID</th>
                            <th scope="col">Task</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($task = $completedTasks->fetch_assoc()): ?>
                            <tr>
                                <td><input class="form-check-input border border-dark rounded-0" type="checkbox" value="<?= $task['id']; ?>" id="flexCheckDefault"></td>
                                <td><?= $task['id']; ?></td>
                                <td><?= $task['tasks']; ?></td>
                                <td><?= date("jS M, Y", strtotime($task['date'])); ?></td>
                                <td>
                                    <a class="delete" data-taskid="<?= $task['id']; ?>" href="">Delete</a> |
                                    <a class="incomplete" data-taskid="<?= $task['id']; ?>" href="">Mark Incomplete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Complete Task Ends -->

    <!-- Pending Task Starts -->
    <?php if ($pendingTasks->num_rows == 0): ?>
        <p>No Task Found</p>
    <?php else: ?>
        <div class="container text-center mt-4">
            <form action="tasks.php" method="POST">
                <div class="row">
                    <h5 class="mb-4">Pending Tasks</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">ID</th>
                                <th scope="col">Task</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($task = $pendingTasks->fetch_assoc()): ?>
                                <tr>
                                    <td><input class="form-check-input border border-dark rounded-0" name="taskids[]" type="checkbox" value="<?= $task['id']; ?>" id="flexCheckDefault"></td>
                                    <td><?= $task['id']; ?></td>
                                    <td><?= $task['tasks']; ?></td>
                                    <td><?= date("jS M, Y", strtotime($task['date'])); ?></td>
                                    <td>
                                        <a class="delete" data-taskid="<?= $task['id']; ?>" href="">Delete</a> |
                                        <a class="complete" data-taskid="<?= $task['id']; ?>" href='#'>Complete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-row my-3">
                    <div class="w-20 mx-3">
                        <select class="form-select" id="bulkAction" name="action" aria-label="Disabled select example">
                            <option selected>Select</option>
                            <option value="bulkDelete">Delete</option>
                            <option value="bulkComplete">Mark As Complete</option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary rounded-1" id="bulkSubmit" value="submit">
                </div>
            </form>
        </div>
    <?php endif; ?>
    <!-- Pending Task Ends -->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <h2 class="my-4">Add task</h2>
            <div class="col-6">
                <form action="tasks.php" method="POST">
                    <?php if (!empty($_GET['added'])): ?>
                        <p>Task Successfully added!</p>
                    <?php endif; ?>
                    <div class="form-group mb-4 mt-3">
                        <label for="" class="form-label">Task</label>
                        <input type="text" class="form-control" id="task" name="task" placeholder="Task">
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <input type="submit" class="btn btn-primary rounded-1 my-3" value="Add Task">
                    <input type="hidden" name="action" value="add">
                </form>
            </div>
        </div>
    </div>

    <form action="tasks.php" method="POST" id="completeForm">
        <input type="hidden" name="action" value="complete">
        <input type="hidden" id="taskid" name="taskid">
    </form>

    <form action="tasks.php" method="POST" id="deleteForm">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" id="deleteTaskID" name="taskid">
    </form>

    <form action="tasks.php" method="POST" id="incompleteForm">
        <input type="hidden" name="action" value="incomplete">
        <input type="hidden" id="incompleteTaskID" name="taskid">
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
    <script>
        ;(function($){
            $(document).ready(function(){
                $(".complete").on("click",function(e){
                    e.preventDefault();
                    let id = $(this).data("taskid");
                    $("#taskid").val(id);
                    $("#completeForm").submit();
                });

                $(".delete").on("click",function(e){
                    e.preventDefault();
                    if(confirm("Are you sure to delete this task ?")) {
                        let id = $(this).data("taskid");
                        $("#deleteTaskID").val(id);
                        $("#deleteForm").submit();
                    }
                });

                $(".incomplete").on("click", function(e){
                    e.preventDefault();
                    let id = $(this).data("taskid");
                    $("#incompleteTaskID").val(id);
                    $("#incompleteForm").submit();
                });

                $("#bulkSubmit").on("click", function(){
                    if($("#bulkAction").val() == "bulkDelete") {
                        if(!confirm("Are you sure to delete tasks ?")) {
                            return false;
                        }
                    }
                });
            })
        })(jQuery)
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>