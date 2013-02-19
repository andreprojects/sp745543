<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;

class LoginController extends AbstractActionController {
    
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
				  
                  return $this->redirect()->toRoute("home");
              }else{
              	  $getIdentity = $result->getIdentity();
                  //var_dump($getIdentity);
                  
                  if(!empty($getIdentity)):
					//echo "No momento seu e-mail não foi confirmado. Clique aqui para confirmar.";
                    
					$repository = $this->getEm()->getRepository("Application\Entity\Users");
        			$objRecordUser = $repository->findByEmail($obj_post_array['email']);
					
					if(!empty($objRecordUser)):
						$recordsBaseUser = $objRecordUser->getArrayCopy();
						
						$recordsLogin['email'] = $recordsBaseUser['email'];
						$recordsLogin['token'] = $recordsBaseUser['token'];
	                    $service->SendEmail($recordsLogin);
	                    
	                    $msg['falert']['ref'] 	  = "login";	
					  	$msg['falert']['cod_msg'] = "1";
					endif;
           	   	  else:
                  	$msg['ferror']['ref'] 	  = "login";	
				  	$msg['ferror']['cod_msg'] = "1";
				  endif;
              }
              
            }
        }

        return new ViewModel(array('form' => $form,'dados' => $msg));    

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
