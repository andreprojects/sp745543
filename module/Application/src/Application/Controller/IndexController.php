<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;

class IndexController extends AbstractActionController {
    
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
        $request = $this->getRequest();
        $error = false;
        
        $form = new \Login\Form\LoginForm($request->getbaseUrl());
        
        if ($request->isPost()) {
        	
            $obj_post = $request->getPost();
            
            $form->setData($obj_post);
            
            if ($form->isValid()) {
              
              //convert to array
              $obj_post_array = $obj_post->toArray();
              
              $auth = new AuthenticationService;

              $sessionStorage = new SessionStorage("Login");
              $auth->setStorage($sessionStorage);
              
			  
              $service = $this->getServiceLocator()->get("service_changepassword");
              $authAdapter = $this->getServiceLocator()->get('Login\Auth\Adapter');
              $authAdapter->setUsername($obj_post_array['email'])
                          ->setPassword($service->encryptPassword($obj_post_array['senha']));
        
              $result = $auth->authenticate($authAdapter);
				
			       if ($result->isValid()) {
                  //var_dump($auth->getIdentity());
                  $getIdentity = $result->getIdentity();
                  $getIdentity['user']->senha = null;
                  
                  //var_dump($getIdentity);exit;
                  //$sessionStorage->write($getIdentity['user'], null);       
                  $msg['fsuccess']['ref']  	  = "login";	
        				  $msg['fsuccess']['cod_msg'] = "1";
        				  
        				  $session = new Container('user');
        				  $session->offsetSet('credito', $getIdentity['user']->credito);
				  
                  return $this->redirect()->toRoute("meus-anuncios");
              }else{
                  	
                  	$msg['ref'] 	= "login";
          					$msg['tipo']	= "error";	
          				  $msg['cod_msg'] = "1";
              }
              
            }
        }

        return new ViewModel(array('form' => $form,'msg' => $msg));    

    }

	public function logoutAction() {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage('Login'));
        $auth->clearIdentity();
        $session = new \Zend\Session\Container('carrinho'); 
        $session->getManager()->destroy(); 
        return $this->redirect()->toRoute('home');
    }
    


}
