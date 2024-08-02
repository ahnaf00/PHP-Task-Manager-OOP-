<?php

include_once "../config/config.php";
include_once "../classes/TaskManager.php";

$taskManager = new TaskManager();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $action = $_POST['action'];

    switch($action)
    {
        case 'add':
            $task = $_POST['task'];
            $date = $_POST['date'];

            if($taskManager->addTask($task, $date))
            {
                header("Location:index.php?added=1");
            }
            else
            {
                echo "Error adding task";
            }
            break;
        
        case 'complete':
            $taskId = $_POST['taskid'];
            if($taskManager->completeTaskStatus($taskId,1))
            {
                header("Location:index.php");
            }
            else
            {
                echo "Error marking task as complete";
            }
            break;
        
        case 'incomplete':
            $taskId = $_POST['taskid'];
            if($taskManager->completeTaskStatus($taskId,0))
            {
                header("Location:index.php");
            }
            else
            {
                echo "Error marking task as incomplete";
            }
            break;

        case 'delete':
            $taskId = $_POST['taskid'];
            if($taskManager->deleteTask($taskId))
            {
                header("Location:index.php");
            }
            else
            {
                echo "Error deleting task";
            }
            break;

        case 'bulkDelete':
            $taskIds = $_POST['taskids'] ?? [];
            foreach($taskIds as $taskId)
            {
                $taskManager->deleteTask($taskId);
            }
            header("Location:index.php");
            break;

        case 'bulkComplete':
            $taskIds = $_POST['taskids']??[];
            foreach($taskIds as $taskId)
            {
                $taskManager->completeTaskStatus($taskId,1);
            }
            header("Location:index.php");
            break;


        default:
            echo "Invalid action";
    }
}