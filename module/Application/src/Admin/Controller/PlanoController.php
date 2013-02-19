<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Renderer\PhpRenderer,
	Zend\View\Model\ViewModel;

class PlanoController extends AbstractActionController {
	
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
		
		$form = $this->getServiceLocator()->get("service_plano_form");
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				
				$service = $this->getServiceLocator()->get("service_plano");
				$records = $request->getPost()->toArray();
				//$records['token'] = md5(uniqid(time()));
				//var_dump($records);
				$service->insert($records);
				
				$msg['ref'] 	= "plano1";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "1";
				$form->setData(array('nome'=>'','descricao'=>'','preco'=>'','dia_publicacao'=>''));
				
				//$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
			}
		}
		
		$repository = $this->getEm()->getRepository("Application\Entity\Plano");
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		return new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));     
	}

	public function editAction()
	{
		
		$form = $this->getServiceLocator()->get("service_plano_form");
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				
				$service = $this->getServiceLocator()->get("service_plano");
				$records = $request->getPost()->toArray();
				//$records['token'] = md5(uniqid(time()));
				//var_dump($records);
				$service->update($records);
				
				$msg['ref'] 	= "servico";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "2";
				//$form->setData(array('nome'=>''));
				
				//$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
			}
		}
		$repository = $this->getEm()->getRepository("Application\Entity\Plano");
		
		$id = $this->params()->fromRoute('id', 0);

		$obj_record_edit = $repository->findByPlano($id);
		
		if(!empty($obj_record_edit)){
			// var_dump($obj_record_edit->getArrayCopy());
			 $form->setData($obj_record_edit->getArrayCopy());
		}
		
		
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		$new_model = new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));
		//$new_model->setTerminal(true);
		$new_model->setTemplate('admin/plano/index');
		return $new_model;		
	}

	public function deleteAction()
	{
		$service = $this->getServiceLocator()->get("service_plano");
		$id = $this->params()->fromRoute('id', 0);
		$service->delete($id);
		return $this->redirect()->toRoute('admin/plano');
		
	}
	



}
