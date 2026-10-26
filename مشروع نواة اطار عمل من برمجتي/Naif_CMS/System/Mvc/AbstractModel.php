<?php

namespace NAIF_CMS\System\Mvc;

use NAIF_CMS\System\Helper\Helper;
use NAIF_CMS\System\Library\Database;
use NAIF_CMS\System\Library\Pagination;

abstract class AbstractModel
{

    use Helper;

    private static $instance;
    private static $db;

    protected static function db()
    {
        if (is_null(self::$db)) {
            self::setDb(Database::getInstance());
        }
        return self::$db;
    }

    protected static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function setDb($db)
    {
        self::$db = $db;
    }

    private function nameParameterSQL()
    {
        $sql = [];
        foreach (static::$tableSchema as $columnName) {
            if (!empty($this->$columnName) && $this->$columnName === 'EmptyField') {
                $sql[] = "`$columnName` = ''";
            } else {
                $sql[] = "`$columnName` = '" . $this->$columnName . "'";
            }
        }
        $sql = implode(' ,', $sql);
        return $sql;
    }

    private static function processingArray($array)
    {
        $sql = [];
        foreach ($array as $name => $value) {
            $sql[] = "`$name` = '$value'";
        }
        $sql = implode(' AND ', $sql);
        return $sql;
    }

    public function save()
    {
        return $this->{static::$primaryKey} !== null ? $this->update() : $this->create();
    }

    private function create()
    {
        $sql  = 'INSERT INTO `' . self::getTableName() . '` SET ' . $this->nameParameterSQL();
        $stmt = self::db()->basicQuery($sql);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function update()
    {
        $id   = $this->filterInt($this->{static::$primaryKey});
        $stmt = self::db()->update(self::getTableName(), $this->nameParameterSQL(), static::$primaryKey, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $id   = $this->filterInt($this->{static::$primaryKey});
        $stmt = self::db()->delete(self::getTableName(), static::$primaryKey, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function deleteBy($array)
    {
        $sql  = self::processingArray($array);
        $stmt = self::db()->basicQuery('DELETE FROM `' . self::getTableName() . '` WHERE ' . $sql);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function checkByPK($id)
    {
        $id   = self::getInstance()->filterInt($id);
        $stmt = self::db()->query('*', self::getTableName(), 'WHERE ' . static::$primaryKey . ' = ' . $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }

    public static function checkBy($array)
    {
        $sql  = self::processingArray($array);
        $stmt = self::db()->query('*', self::getTableName(), 'WHERE ' . $sql);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }

    public static function isExists($id, $array)
    {
        $id   = self::getInstance()->filterInt($id);
        $sql  = self::processingArray($array);
        $sql  = 'SELECT * FROM `' . self::getTableName() . '` WHERE ' . $sql . ' AND ' . static::$primaryKey . ' != ' . $id;
        $stmt = self::db()->basicQuery($sql);
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    public static function getByPK($id)
    {
        $id   = self::getInstance()->filterInt($id);
        $stmt = self::db()->query('*', self::getTableName(), 'WHERE ' . static::$primaryKey . ' = ' . $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchObject(static::class);
            }
        }
        return false;
    }

    public static function getBy($array)
    {
        $sql  = self::processingArray($array);
        $stmt = self::db()->query('*', self::getTableName(), 'WHERE ' . $sql);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = [];
                while ($row  = $stmt->fetchObject(static::class)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public static function get($sql)
    {
        $stmt = self::db()->basicQuery($sql);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = [];
                while ($row  = $stmt->fetchObject(static::class)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public static function getAll($pagination = false, $sql = null)
    {
        $PaginationClass = Pagination::getInstance();
        if (is_null($sql)) {
            if ($pagination) {
                $paginationSQL = 'LIMIT ' . $PaginationClass->limitPage() . ' , ' . PAGINATION_COUNTER;
                $stmt          = self::db()->query('*', self::getTableName(), $paginationSQL);
            } else {
                $stmt = self::db()->query('*', self::getTableName());
            }
        } else {
            $stmt = self::db()->basicQuery($sql);
        }
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = [];
                while ($row  = $stmt->fetchObject(static::class)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public static function find($columnName, $keyword, $pagination = false, $sql = null)
    {
        $PaginationClass = Pagination::getInstance();
        $keyword         = self::getInstance()->filterString($keyword);
        if ($pagination) {
            $paginationSQL = 'LIMIT ' . $PaginationClass->limitPage() . ' , ' . PAGINATION_COUNTER;
            $stmt          = self::db()->find(self::getTableName(), $columnName, $keyword, "$sql $paginationSQL");
        } else {
            $stmt = self::db()->find(self::getTableName(), $columnName, $keyword, $sql);
        }
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = [];
                while ($row  = $stmt->fetchObject(static::class)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public static function insertId()
    {
        return self::db()->insertId();
    }

    public static function getTableName()
    {
        return static::$tableName;
    }

    public static function getTableCount()
    {
        return self::db()->query('*', self::getTableName())->rowCount() === 0 ? false : self::db()->rowCount();
    }

}