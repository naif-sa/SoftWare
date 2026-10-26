<?php

namespace NAIF_CMS\System\Library;

class Application
{

    private static $instance;

    private function __construct()
    {
        // ... //
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run($templateConfig)
    {
        $Encryption             = Encryption::getInstance();
        $SessionManager         = SessionManager::getInstance($Encryption);
        $SessionManager->start();
        $Template               = Template::getInstance($templateConfig);
        $Database               = Database::getInstance();
        $Language               = Language::getInstance();
        $Registry               = Registry::getInstance();
        $Registry->session      = $SessionManager;
        $Registry->Language     = $Language;
        $Registry->Database     = $Database;
        $Registry->Encryption   = $Encryption;
        $Registry->RandomData   = RandomData::getInstance();
        $Registry->Messenger    = Messenger::getInstance($SessionManager);
        $Registry->Request      = Request::getInstance($SessionManager);
        $Registry->Datetime     = Datetime::getInstance($Language);
        $Registry->Pagination   = Pagination::getInstance($Database, $Language);
        $Registry->TableManager = TableManager::getInstance($Database);
        $FrontController        = FrontController::getInstance();
        $FrontController->setRegistry($Registry);
        $FrontController->setTemplate($Template);
        $FrontController->dispatch();
    }

}