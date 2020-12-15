<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

//extends AbstractRestfulController
class ApiController extends AbstractActionController 
{
    public function indexAction()
    {
        // return new JsonModel((array)$greeting);
        return new ViewModel();
    }
}