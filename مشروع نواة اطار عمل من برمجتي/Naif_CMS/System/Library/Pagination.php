<?php

namespace NAIF_CMS\System\Library;

use NAIF_CMS\System\Helper\Helper;

class Pagination
{

    use Helper;

    private static $instance;
    private $db;
    private $Language;
    private $tableCount;
    private $countPage;
    private $page;
    private $tableName;
    private $SQLQuery;

    private function __construct($db, $Language)
    {
        $this->setDb($db);
        $this->setLanguage($Language);
        $this->setPage(isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page']) ? $this->filterInt($_GET['page']) : 1);
    }

    public static function getInstance($db = null, $Language = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($db, $Language);
        }
        return self::$instance;
    }

    public function setLanguage($Language)
    {
        $this->Language = $Language;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setSQLQuery($SQLQuery)
    {
        $this->SQLQuery = $SQLQuery;
    }

    private function totalPage()
    {
        if (is_null($this->SQLQuery)) {
            $stmt = $this->db->query('*', $this->tableName);
        } else {
            $stmt = $this->db->basicQuery($this->SQLQuery);
        }
        if ($stmt->execute()) {
            $this->tableCount = $stmt->rowCount();
            $this->countPage  = ceil($this->tableCount / PAGINATION_COUNTER);
            return $this->countPage;
        }
        return false;
    }

    public function display()
    {
        $totalPage = $this->totalPage();
        $content   = '';
        if ($totalPage) {
            if ($this->page > 1) {
                $content .= '<a class="paginationText" href="?page=1">' . $this->Language->get('text_Pagination_frontPage') . '</a>&nbsp;';
                $content .= '<a class="paginationText" href="?page=' . ($this->page - 1) . '">' . $this->Language->get('text_Pagination_backPage') . '</a>&nbsp;';
            }
            $start = $this->page - 2;
            if ($start < 1) {
                $start = 1;
            }
            $end = $this->page + 4;
            if ($end > $totalPage) {
                $end = $totalPage;
            }
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $this->page) {
                    $content .= '<span class="paginationActiveLink">' . $i . '</span>&nbsp;';
                } else {
                    $content .= '<a class="paginationLink" href="?page=' . $i . '">' . $i . '</a>&nbsp;';
                }
            }
            if ($this->page < $totalPage) {
                $content .= '<a class="paginationText" href="?page=' . ($this->page + 1) . '">' . $this->Language->get('text_Pagination_nextPage') . '</a>&nbsp;';
                $content .= '<a class="paginationText" href="?page=' . $totalPage . '">' . $this->Language->get('text_Pagination_endPage') . '</a>';
            }
            return $content;
        }
        return false;
    }

    public function limitPage()
    {
        return ($this->page - 1) * PAGINATION_COUNTER;
    }

}