<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container; 

class IndicadoController extends AbstractActionController {
    
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
        
        $token  = $this->params('token', false);
        $id     = $this->params('id', false);
		
		

        $repository_convite  = $this->getEm()->getRepository("Application\Entity\Convite");
        $obj_rec_convite     = $repository_convite->findByTokenAndId($token,$id);

        if(!empty($obj_rec_convite)){
            $repository_user  = $this->getEm()->getRepository("Application\Entity\Usuario");
			$obj_rec_user     = $repository_user->findById($obj_rec_convite->id_usuario);
            
			$obj_check_user = $repository_user->findByEmail($obj_rec_convite->email);
			//var_dump($obj_rec_convite->email,$obj_rec_user->email);
			if(!empty($obj_check_user)){
				return $this->redirect()->toRoute('home-message');
				exit;
			}
			
            $records = $obj_rec_convite->getArrayCopy();

            if(!empty($obj_rec_user)){
                $load_records['nome_remetente'] = $obj_rec_user->nome;
                $load_records['email_remetente'] = $obj_rec_user->email;
                $load_records['email'] = $obj_rec_convite->email;
                $load_records['token'] = $token;
                $load_records['id']    = $id;
            }
            
            //var_dump($load_records);

            $form = $this->getServiceLocator()->get("indicado_step2_form");
            $request = $this->getRequest();

            $form->setData($load_records);
            
            if ($request->isPost()) {
                //var_dump($request->getPost());
                $form->setData($request->getPost());
                
                if ($form->isValid()) {
                	
                    $service = $this->getServiceLocator()->get("service_register");
                    $records = $request->getPost()->toArray();
                    $records['token'] = md5(uniqid(time()));
                    $data = new \DateTime("now America/Sao_Paulo");
                    $records['data_alteracao']  = $data;
                    $records['senha']           = $service->encryptPassword($records['senha']);
                    //$records['status']        = 10;
                    
                    //var_dump($records);
                    $result_insert = $service->insert($records);
                    //$service->SendEmail($records);    
                    
                    //var_dump($result_update);
                    if(!empty($result_insert))
                    {
                    	$records['id'] = $result_insert->id;
                    	$records['diretorio'] = 'files/'.$data->format("Y").'/'.$data->format("m").'/'.$data->format("d").'/'.$records['id'].'/';
                    	$service->update($records);
                    	
						$records_up_user['id'] = $obj_rec_user->id;
						$records_up_user['qtd_anuncio'] = $obj_rec_user->qtd_anuncio + 1;
						$service->update($records_up_user);	
						
                        $auth = new AuthenticationService;
    
                        $sessionStorage = new SessionStorage("Login");
                        $auth->setStorage($sessionStorage);
                        
                        //$email = "andrework@gmail.com";
                        //$records['senha'] = "0ab478795daa5b428b51e548a69414f94a4da5380ae0cd92198694afc52bd0bb58347762a4037efce9d8b03736aa2250dce454706346893e0ed0b388f13f17d0";
                        
                        //$service = $this->getServiceLocator()->get("service_changepassword");
                        $authAdapter = $this->getServiceLocator()->get('Login\Auth\Adapter');
                        $authAdapter->setUsername($records['email'])
                                    ->setPassword($records['senha']);
                        
                        $result = $auth->authenticate($authAdapter);

                        if ($result->isValid()) {
                            $getIdentity = $result->getIdentity();
                            $getIdentity['user']->senha = null;
                            $session = new Container('user');
                            $session->offsetSet('credito', $getIdentity['user']->credito);
                                
                            return $this->redirect()->toRoute('meus-anuncios');
                        }else{
                            return $this->redirect()->toRoute('home-message',array('tipo'=>'success','ref'=>'register','cod_msg'=>'2'));
                        }
                    }
                }
                    
                
            }

        }

        $result = new ViewModel(array('form' => $form,'records' => $load_records,'msg' => $msg));
    	//$result->setTerminal(true);
		return $result;     
    }



}
