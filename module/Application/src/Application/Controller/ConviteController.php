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
                //$records['token'] = md5(uniqid(time()));
                $records['id_usuario'] = $sessionLogin['user']->id;
                //var_dump($records);

                $repository = $this->getEm()->getRepository("Application\Entity\Convite");
                $obj_records = $repository->findByEmail($records['email']);

                if(!empty($obj_records))
                {
                    $records_prepare = $obj_records->getArrayCopy();
                    $records_prepare['nome'] = $records['nome'];
                    //var_dump($obj_records,$obj_records->nome);
                    //var_dump($obj_records->toArray());
                    //$service->insert($records);
    				
    				$msg['ref'] 	= "convite";
    				$msg['tipo']	= "success";	
    				$msg['cod_msg'] = "1";
    				$form->setData(array('nome'=>'','email'=>''));
    				
                    //$service->SendEmail($records);    
    				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
                }
            }
        }

        $result = new ViewModel(array('form' => $form,'msg' => $msg));
    	$result->setTerminal(true);
		return $result;     
    }


}
