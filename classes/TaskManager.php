<?php

include "Database.php";

class TaskManager{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = new Database();
        $this->connection = $this->db->connect();
    }

    public function getPendingTasks()
    {
        $query = "SELECT * FROM tasks WHERE complete=0 ORDER BY date";
        $result = $this->connection->query($query);
        return $result;
    }

    public function getCompletedTasks()
    {
        $query = "SELECT * FROM tasks WHERE complete=1 ORDER BY date";
        $result = $this->connection->query($query);
        return $result;
    }

    public function addTask($task, $date)
    {
        $stmt = $this->connection->prepare("INSERT INTO tasks (tasks, date) VALUES (?, ?)");
        $stmt->bind_param("ss",$task, $date);
        return $stmt->execute();
    }

    public function completeTaskStatus($taskId,$status)
    {
        $stmt = $this->connection->prepare("UPDATE tasks SET complete=? WHERE id= ?");
        $stmt->bind_param("ii",$status,$taskId);
        return $stmt->execute();
    }

    public function deleteTask($taskId)
    {
        $stmt = $this->connection->prepare("DELETE FROM tasks WHERE id= ?");
        $stmt->bind_param("i", $taskId);
        return $stmt->execute();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}