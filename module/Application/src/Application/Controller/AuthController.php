<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container; 

class AuthController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
	
	protected $sessionLogin;
	
	    
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
    	//$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");

        //Verificar se já está cadastrado
        
        $token    = $this->params('token', false);
        $email    = $this->params('email', false);
        $id_anuncio  = $this->params('id_anuncio', false);
        $id_pergunta  = $this->params('id_pergunta', false);
        		

        $repository  = $this->getEm()->getRepository("Application\Entity\Usuario");
        $obj_records         = $repository->findByTokenAndEmail($token,$email);
        var_dump($obj_records);exit;
        if(!empty($obj_records)){

						
            $auth = new AuthenticationService;

            $sessionStorage = new SessionStorage("Login");
            $auth->setStorage($sessionStorage);
            
            //$email = "andrework@gmail.com";
            //$records['senha'] = "0ab478795daa5b428b51e548a69414f94a4da5380ae0cd92198694afc52bd0bb58347762a4037efce9d8b03736aa2250dce454706346893e0ed0b388f13f17d0";
            
            //$service = $this->getServiceLocator()->get("service_changepassword");
            $authAdapter = $this->getServiceLocator()->get('Login\Auth\Adapter');
            $authAdapter->setUsername($obj_records['email'])
                        ->setPassword($obj_records['senha']);
            
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $getIdentity = $result->getIdentity();
                $getIdentity['user']->senha = null;
                $session = new Container('user');
                $session->offsetSet('credito', $getIdentity['user']->credito);
                    
                return $this->redirect()->toRoute($session);
            }else{
                return $this->redirect()->toRoute('home-message');
            }
        }

        //$result = new ViewModel(array('form' => $form,'records' => $load_records,'msg' => $msg));
    	//$result->setTerminal(true);
		return $this->response;     
    }



}
