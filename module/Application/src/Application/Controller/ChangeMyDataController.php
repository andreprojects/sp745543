<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class ChangeMyDataController extends AbstractActionController {
    
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
    
    public function indexAction(){
    	
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}

        $records = $form = array();
        
        $repository = $this->getEm()->getRepository("Application\Entity\Users");
        $obj_records_users = $repository->findById($sessionLogin['user']->id);
		
		//var_dump($obj_records_users);exit;
        
        if(!empty($obj_records_users)){

            $records = $obj_records_users->getArrayCopy();
				
			if(empty($records)):
				return $this->redirect()->toRoute('home-message',array('tipo'=>'ferror','ref'=>'register','cod_msg'=>'1'));
				exit;
			endif;
			
            $form = $this->getServiceLocator()->get("service_change_my_data_form");
            $records['senha'] = '';
            $form->setData($records);
            
            $request = $this->getRequest();
        
            if ($request->isPost()) {
                $form->setData($request->getPost());
				
                if ($form->isValid()) {
                	
                    $service = $this->getServiceLocator()->get("service_changemydata");
                    $records = $request->getPost()->toArray();
                    $data = new \DateTime("now America/Sao_Paulo");
                    $records['data_alteracao']  = $data;
                    //$records['diretorio']       = 'files/'.$data->format("Y").'/'.$data->format("m").'/'.$data->format("d").'/'.$records['id'].'/';
                    $records['senha']           = $service->encryptPassword($records['senha']);
                    $service->update($records);
                    //$service->SendEmail($records);    
                    return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'4'));
                }
                //$form->setData($obj_records_users); 
            }
        }
        
        return new ViewModel(array('form'=>$form,'records'=>$records));
    }
	


}
