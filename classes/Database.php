<?php

class Database{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;
    private $connection;

    public function connect()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if($this->connection->connect_error)
        {
            throw new Exception("Can not connect to the database: ".$this->connection->connect_error);
        }

        return $this->connection;
    }

    public function close()
    {
        $this->connection->close();
    }
}
