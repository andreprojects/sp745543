<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

class MeusAnunciosController extends AbstractActionController {
    
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
    	
        $form = $this->getServiceLocator()->get("service_meusanuncios_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_meusanuncios");
                $records = $request->getPost()->toArray();
                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);
                $service->insert($records);
				
				$msg['ref'] 	= "meusanuncios";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "1";
				$form->setData(array('titulo'=>''));
				
                //$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
            }
        }
		
		$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		return new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));     
    }

	public function editAction()
	{
		
        $form = $this->getServiceLocator()->get("service_meusanuncios_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_meusanuncios");
                $records = $request->getPost()->toArray();
                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);
                $service->update($records);
				
				$msg['ref'] 	= "meusanuncios";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "2";
				//$form->setData(array('nome'=>''));
				
                //$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
            }
        }
		$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		
		$id = $this->params()->fromRoute('id', 0);

		$obj_record_edit = $repository->findByAnuncio($id);
		
		if(!empty($obj_record_edit)){
			// var_dump($obj_record_edit->getArrayCopy());
			 $form->setData($obj_record_edit->getArrayCopy());
		}
		
		
		$obj_records = $repository->fetchPairs();
		
		//var_dump($obj_records->getArrayCopy());
		$new_model = new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$obj_records));
		//$new_model->setTerminal(true);
		$new_model->setTemplate('application/meus-anuncios/index');
		return $new_model;		
	}

	public function addimageAction(){
		
		$id_anuncio = $this->params()->fromRoute('id', 0);
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		if(empty($id_anuncio)){
			json_encode(array('files'=>array('error'=>'Erro id. Tente Novamente')));
			exit;
		}
		
		$path_folder = "./public/users/".$sessionLogin['user']->diretorio.$id_anuncio;
		
		if(!file_exists($path_folder))
		{
			$create_dir = mkdir($path_folder,0755,true);
			if(!$create_dir){
				json_encode(array('files'=>array('error'=>'Erro ao criar diretório. Tente Novamente')));
				exit;
			}
		}
		$file = $this->params()->fromFiles('filesaddfotos');
		//var_dump($id,$path_folder,$file,$file['name'],$_FILES);exit;
		
		$size = new Size(array('min'=>100,'max'=>512000)); //minimum bytes filesize
        $extension = new Extension(array("extension" => array("jpg","gif","png")));
        
        $adapter = new \Zend\File\Transfer\Adapter\Http(); 
        $adapter->setValidators(array($size,$extension), $file['name']);
		
        if (!$adapter->isValid()){
            $dataError = $adapter->getMessages();
            $error = array();
            foreach($dataError as $key=>$row)
            {
                //$error[] = $row;
                echo json_encode(array('files'=>array('error'=>$row)));
				exit;
            }  
        } else {
        	$files_current_count = count(scandir($path_folder,1))-1;
			$adapter->setDestination($path_folder);
			$adapter->addFilter('Rename', array('target' => $path_folder.'/'.$files_current_count."-".$file['name'],
             'overwrite' => true));
            if ($adapter->receive()) {
            	echo json_encode(array('files'=>array($file+array('pathfull'=>$path_folder.'/'.$files_current_count."-".$file['name']))));
			}
		}
		
		//var_dump($error);exit;
		//echo json_encode($_FILES);
		//$viewModel = new ViewModel();
		//$viewModel->setTerminal(true);
		return $this->response;
		//return $viewModel;
		
	}

	public function deleteAction()
	{
		$service = $this->getServiceLocator()->get("service_servico");
		$id = $this->params()->fromRoute('id', 0);
		$service->delete($id);
		return $this->redirect()->toRoute('admin/servico');
		
	}
    



}
