<?php

/*=======================================================================*\
|| ##################################################################### ||
|| ## Class Name            :             Database                    ## ||
|| ## Version               :              1.0.13                     ## ||
|| ## Programmer            :          Naif AL-Juhani                 ## ||
|| ## Date                  :          2026 - 09 - 19                 ## ||
|| ## Description Of Class  : Connect MySQL Database By MySQLi Driver ## ||
|| ##################################################################### ||
\*=======================================================================*/

class Database
{

	private static $instance;
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $port;
    private $stmt;
    private $dbh;

    private function __construct($host, $user, $pass, $dbname, $port)
    {
        $this->host   = $host;
        $this->user   = $user;
        $this->pass   = $pass;
        $this->dbname = $dbname;
        $this->port   = $port;
        $this->connect();
    }

    public static function getInstance($host, $user, $pass, $dbname, $port = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($host, $user, $pass, $dbname, $port);
        }
        return self::$instance;
    }

    private function connect()
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->dbh = @mysqli_connect($this->host, $this->user, $this->pass, $this->dbname, $this->port);
        if ($this->dbh) {
            mysqli_set_charset($this->dbh, 'UTF8');
        } else {
            die('Database Connect Error');
        }
    }

    public function query($column, $table, $other = null)
    {
        $this->stmt = mysqli_query($this->dbh, "SELECT {$column} FROM `{$table}` {$other}");
        return $this;
    }

    public function basicQuery($sql)
    {
        $this->stmt = mysqli_query($this->dbh, $sql);
        return $this;
    }

    public function insert($table, $column, $value)
    {
        $this->stmt = mysqli_query($this->dbh, "INSERT INTO `{$table}` ({$column}) VALUES ({$value})");
        return $this;
    }

    public function update($table, $data, $column = null, $id = null)
    {
        $this->stmt = mysqli_query($this->dbh, "UPDATE `{$table}` SET {$data}" . ($column ? " WHERE {$column} = '{$id}'" : ''));
        return $this;
    }

    public function delete($table, $column, $id)
    {
        $this->stmt = mysqli_query($this->dbh, "DELETE FROM `{$table}` WHERE {$column} = '{$id}'");
        return $this;
    }

    public function find($table, $column, $keyword, $other = null)
    {
        $this->stmt = mysqli_query($this->dbh, "SELECT * FROM `{$table}` WHERE {$column} LIKE'%{$keyword}%' {$other}");
        return $this;
    }

    public function execute()
    {
        return $this->stmt;
    }

    public function rowCount()
    {
        return mysqli_num_rows($this->stmt);
    }

    public function fetchAssoc()
    {
        return mysqli_fetch_all($this->stmt, MYSQLI_ASSOC);
    }

    public function fetchField()
    {
        return mysqli_fetch_fields($this->stmt);
    }

    public function fetchArray($type = null)
    {
        return mysqli_fetch_array($this->stmt, $type);
    }

    public function fetchRow()
    {
        return mysqli_fetch_row($this->stmt);
    }

    public function fetchObject($className = null)
    {
        return mysqli_fetch_object($this->stmt, $className);
    }

    public function freeResult()
    {
        return mysqli_free_result($this->stmt);
    }

    public function escapeString($value)
    {
        return mysqli_real_escape_string($this->dbh, $value);
    }

    public function insertId()
    {
        return mysqli_insert_id($this->dbh);
    }

    public function __destruct()
    {
        mysqli_close($this->dbh);
    }

}