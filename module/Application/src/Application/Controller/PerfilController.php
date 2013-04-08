<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;

class PerfilController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
        /*
     * @return EntityManager
     */

    protected function getEm() {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;
    }

    public function indexAction()
    {

        $msg = array();
        //$this->params()->fromRoute('action', 0);
        $username = $this->params()->fromRoute('username', 0);

        if(empty($username)){
          return $this->redirect()->toRoute("home-message");
        }

        $dados['username'] = $username;
        return new ViewModel(array('form' => $form,'msg' => $msg,'dados' => $dados));    

    }
    


}
