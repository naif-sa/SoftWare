<?php

namespace NAIF_CMS\FrontEND\Controller;

use NAIF_CMS\System\Mvc\AbstractController;

class HomeController extends AbstractController
{

    public function defaultAction()
    {
        $this->Language->load('Home.default');
        $this->view();
    }

}