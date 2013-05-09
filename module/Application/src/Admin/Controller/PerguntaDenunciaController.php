<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

class PerguntaDenunciaController extends AbstractActionController {
    
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
    	
        $form = $this->getServiceLocator()->get("service_servico_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_servico");
                $records = $request->getPost()->toArray();
                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);
                $service->insert($records);
				
				$msg['ref'] 	= "servico";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "1";
				$form->setData(array('nome'=>''));
				
                //$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
            }
        }
		
		$repository = $this->getEm()->getRepository("Application\Entity\Servico");
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		return new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));     
    }

    public function listAction(){
        //$id_ads   = $this->params()->fromRoute('id_ads', 0);
        $col_order   = $this->params()->fromRoute('col_order', 'id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');

        $repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
        $or_pergunta = $repository->findByDenuncia(array($col_order=>$type_order));//,array($col_order=>$type_order));

        $page = $this->params()->fromRoute('page',1);
        $paginator = new Paginator(new ArrayAdapter($or_pergunta));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(5);

        
        $result = new ViewModel(array('dados'=>$paginator));
        $result->setTerminal(true);
        return $result;
    }

	public function editAction()
	{
		
        $form = $this->getServiceLocator()->get("service_servico_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_servico");
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
		$repository = $this->getEm()->getRepository("Application\Entity\Servico");
		
		$id = $this->params()->fromRoute('id', 0);

		$obj_record_edit = $repository->findByServico($id);
		
		if(!empty($obj_record_edit)){
			// var_dump($obj_record_edit->getArrayCopy());
			 $form->setData($obj_record_edit->getArrayCopy());
		}
		
		
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		$new_model = new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));
		//$new_model->setTerminal(true);
		$new_model->setTemplate('admin/servico/index');
		return $new_model;		
	}

	public function deleteAction()
	{
		$service = $this->getServiceLocator()->get("service_servico");
		$id = $this->params()->fromRoute('id', 0);
		$service->delete($id);
		return $this->redirect()->toRoute('admin/servico');
		
	}
    



}
