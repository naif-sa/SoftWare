<?php

namespace NAIF_CMS\FrontEND\Controller;

use NAIF_CMS\System\Mvc\AbstractController;

class NotFoundController extends AbstractController
{

    public function defaultAction()
    {
        $this->Language->load('NotFound.default');
        $this->view();
    }

}