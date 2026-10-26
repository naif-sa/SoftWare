<?php

namespace NAIF_CMS\System\Library;

class Database
{

	private static $instance;
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;
    private $port;
    private $stmt;
    private $dbh;

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        if (defined('DB_PORT')) {
            $this->port = DB_PORT;
        }
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