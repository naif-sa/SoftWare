<?php

namespace NAIF_CMS\System\Library;

class TableManager
{

    private static $instance;
    private $db;
    private $tableName;
    private $tableSchema;

    private function __construct($db)
    {
        $this->setDb($db);
    }

    public static function getInstance($db)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($db);
        }
        return self::$instance;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setTableSchema($tableSchema)
    {
        $this->tableSchema = $tableSchema;
    }

    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . $this->tableName . '`(';
        foreach ($this->tableSchema as $column => $type) {
            $sql .= '`' . $column . '` ' . $type . ' ,';
        }
        $sql  = trim($sql, ' ,');
        $sql  .= ');';
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function dropTable()
    {
        $sql  = "DROP TABLE IF EXISTS `{$this->tableName}`";
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function optimizeTable()
    {
        $sql  = "OPTIMIZE TABLE `{$this->tableName}`";
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute() === true ? true : false;
    }

    public function truncateTable()
    {
        $sql  = "TRUNCATE TABLE `{$this->tableName}`";
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function repairTable()
    {
        $sql  = "REPAIR TABLE `{$this->tableName}`";
        $stmt = $this->db->basicQuery($sql);
        if ($stmt->execute()) {
            foreach ($stmt->fetchAssoc() as $status) {
                return $status['Msg_text'] === 'OK';
            }
        }
        return false;
    }

    public function addField($columnName, $type = 'TEXT', $isNull = false)
    {
        $sql  = "ALTER TABLE `{$this->tableName}` ADD {$columnName} {$type} " . ($isNull ? 'NOT NULL' : 'NULL');
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function dropField($columnName)
    {
        $sql  = "ALTER TABLE `{$this->tableName}` DROP {$columnName}";
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function editField($columnName, $type = 'TEXT', $isNull = false)
    {
        $sql  = "ALTER TABLE `{$this->tableName}` MODIFY {$columnName} {$type} " . ($isNull ? 'NOT NULL' : 'NULL');
        $stmt = $this->db->basicQuery($sql);
        return $stmt->execute();
    }

    public function exportToSQL()
    {
        $fileName = 'Backup_' . date('Y-m-d') . '_' . bin2hex(random_bytes(4)) . '.sql';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $tables   = $this->getTables();
        foreach ($tables as $table) {
            echo "\nDROP TABLE IF EXISTS $table;\n";
            $columns = $this->getColumns($table);
            $stmt    = $this->db->basicQuery('SHOW CREATE TABLE ' . $table);
            foreach ($stmt->fetchAssoc() as $schema) {
                echo "\n" . $schema['Create Table'] . ";\n\n";
            }
            $stmt       = $this->db->query('*', $table);
            $countTable = $stmt->rowCount();
            for ($i = 0; $i < $countTable; $i++) {
                $fetch       = $stmt->fetchRow();
                $value       = array_values($fetch);
                echo 'INSERT INTO ' . $table . ' (';
                echo implode(',', $columns) . ') VALUES (';
                $filterValue = array_map(function ($value) {
                    return $value == null ? null : $value;
                }, $value);
                echo "'" . implode("','", $filterValue) . "'";
                echo ");\n";
            }
        }
        exit();
    }

    public function getTables()
    {
        $tables = [];
        $stmt   = $this->db->basicQuery('SHOW TABLES');
        if ($stmt->execute()) {
            foreach ($stmt->fetchAssoc() as $tableName) {
                $tables[] = $tableName['Tables_in_' . DB_NAME];
            }
        }
        return $tables;
    }

    public function getColumns($tableName)
    {
        $columns = [];
        $stmt    = $this->db->query('*', $tableName);
        if ($stmt->execute()) {
            foreach ($stmt->fetchField() as $fetch) {
                $columns[] = $fetch->name;
            }
        }
        return $columns;
    }

}