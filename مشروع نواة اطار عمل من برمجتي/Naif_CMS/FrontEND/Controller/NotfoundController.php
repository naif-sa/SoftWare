<?php

namespace NAIF_CMS\FrontEND\Controller;

use NAIF_CMS\System\Mvc\AbstractController;

class NotfoundController extends AbstractController
{

    public function defaultAction()
    {
        $this->Language->load('Notfound.default');
        $this->view();
    }

}