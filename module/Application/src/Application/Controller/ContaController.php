<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class ContaController extends AbstractActionController {
    
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

		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		$repository_user = $this->getEm()->getRepository("Application\Entity\Usuario");
		$obj_records_user = $repository_user->findById($sessionLogin['user']->id);

		$request = $this->getRequest();

		$form = $this->getServiceLocator()->get("conta_form");

		if(!empty($obj_records_user))
		{
			$records = $obj_records_user->getArrayCopy();
			$form->setData($records);
		}

		if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {

            	$service = $this->getServiceLocator()->get("service_register");
                $records_post = $request->getPost()->toArray();
                $records['nome'] = $records_post['nome'];
                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);

                $service->update($records);
                //$form->setData(array('nome'=>''));

                $msg['ref'] 	= "conta";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "1";

            	

            }

        }

		return new ViewModel(array('form' => $form,'msg' => $msg));

	}


}
