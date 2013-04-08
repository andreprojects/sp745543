<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

class ConviteController extends AbstractActionController {
    
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
    	$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        $form = $this->getServiceLocator()->get("convite_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_convite");
                $records = $request->getPost()->toArray();
                $records['id_usuario'] = $sessionLogin['user']->id;
                //var_dump($records);

                $repository = $this->getEm()->getRepository("Application\Entity\Convite");
                $obj_records = $repository->findByEmail($records['email']);

                if(!empty($obj_records))
                {
                    $records_prepare = $obj_records->getArrayCopy();
					$records_email['id'] = $records_prepare['id'];
					$records_email['token'] = $records_prepare['token'];
                    $records_prepare['nome'] = $records['nome'];
                    $service->update($records_prepare);
                    $msg['ref']     = "convite";
                    $msg['tipo']    = "success";    
                    $msg['cod_msg'] = "2";
                }else{
                	$records_email['token'] = $records['token'] = md5(uniqid(time()));
                    $records_email['id'] = $service->insert($records);
                    $msg['ref']     = "convite";
                    $msg['tipo']    = "success";    
                    $msg['cod_msg'] = "1";
    			}
                $records_email['nome_remetente'] = $sessionLogin['user']->nome;
                $records_email['nome'] = $records['nome'];
                $records_email['email'] = $records['email'];

                $service->setMailSubject($records_email['nome_remetente']." enviou um convite para você");
                $service->SendEmail($records_email);

				
				$form->setData(array('nome'=>'','email'=>''));
				
                    //$service->SendEmail($records);    
    				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
                
            }
        }

        $result = new ViewModel(array('form' => $form,'msg' => $msg));
    	$result->setTerminal(true);
		return $result;     
    }



}
