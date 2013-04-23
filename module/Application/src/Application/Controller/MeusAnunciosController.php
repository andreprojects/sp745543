<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

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

	public function textoURL($string) 
	{ 
        $string    = htmlentities(strtolower($string)); 
        $string    = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $string); 
	    $string    = preg_replace("/([^a-z0-9]+)/", "-", html_entity_decode($string)); 
	    $string    = trim($string, "-"); 
	    return $string; 
	}
	
    public function indexAction()
    {

    	$repository_user = $this->getEm()->getRepository("Application\Entity\Usuario");
        $form = $this->getServiceLocator()->get("service_meusanuncios_form");
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		$request = $this->getRequest();
		$obj_records_user = $repository_user->findById($sessionLogin['user']->id);

        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if($obj_records_user->qtd_anuncio < 1){
				return $this->redirect()->toRoute('meus-anuncios');
			}

            if ($form->isValid()) {
            	
                $service = $this->getServiceLocator()->get("service_meusanuncios");
                $records = $request->getPost()->toArray();
				$records['id_usuario'] = $sessionLogin['user']->id;
 				
 				$records['url'] = $this->textoURL($records['titulo']);
                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);
                $service->insert($records);
				
				$service_user = $this->getServiceLocator()->get("service_register");
				$update_records_user['id'] = $sessionLogin['user']->id;
				$update_records_user['qtd_anuncio'] = $obj_records_user->qtd_anuncio - 1;
				$service_user->update($update_records_user);
				
				$msg['ref'] 	= "meusanuncios";
				$msg['tipo']	= "success";	
				$msg['cod_msg'] = "1";
				$form->setData(array('titulo'=>'','descricao'=>''));
				
                //$service->SendEmail($records);    
				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
            }
        }
		
		$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		$obj_records = $repository->findByUserListAll($sessionLogin['user']->id);
		
		//var_dump($obj_records);
		/*
		$page = $this->params()->fromRoute('page');
		$paginator = new Paginator(new ArrayAdapter($obj_records));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(3);
*/
		
		//var_dump($obj_records->getArrayCopy());
		return new ViewModel(array('form' => $form,
									'msg' => $msg,
									'username' => $sessionLogin['user']->username,
									'dados'=>$obj_records,
									'qtd_anuncio'=>$obj_records_user->qtd_anuncio,
									'dados_pagination'=>$paginator));     
    }

    public function listAction(){
    	$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
    	
    	$col_order   = $this->params()->fromRoute('col_order', 'id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');

    	$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		$obj_records = $repository->findByUserListAll($sessionLogin['user']->id,$col_order,$type_order);


		$page = $this->params()->fromRoute('page');
		$paginator = new Paginator(new ArrayAdapter($obj_records));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(2);

		$result = new ViewModel(array(
									'username' => $sessionLogin['user']->username,
									'dados'=>$paginator,
									)); 
		$result->setTerminal(true);
		return $result;

    }

	public function editAction()
	{
		
        $form = $this->getServiceLocator()->get("service_meusanuncios_form");
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        $request = $this->getRequest();
		
		$id_anuncio = $this->params()->fromRoute('id', 0);
		
		$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		//Verificar se o anuncio pertence ao usuario
		$obj_check_ok = $repository->findByAnuncioAndUser($id_anuncio,$sessionLogin['user']->id);
        
		if(empty($obj_check_ok)){
			return $this->redirect()->toRoute('meus-anuncios');
			exit;
		}
		
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
		
		
		$action = $this->params()->fromRoute('action', 0);

		$obj_record_edit = $repository->findByAnuncio($id_anuncio);
		
		if(!empty($obj_record_edit)){
			// var_dump($obj_record_edit->getArrayCopy());
			 $form->setData($obj_record_edit->getArrayCopy());
		}
		
		
		$obj_records = $repository->findByUserListAll($sessionLogin['user']->id);
		
		//$repository_user = $this->getEm()->getRepository("Application\Entity\Usuario");
		//$obj_records_user = $repository_user->findById($sessionLogin['user']->id);
		
		$new_model = new ViewModel(array(	'form' => $form,
											'msg' => $msg,
											'username' => $sessionLogin['user']->username,
											'dados'=>$obj_records,
											//'qtd_anuncio'=>$obj_records_user->qtd_anuncio,
											'action' => $action));
		//$new_model->setTerminal(true);
		$new_model->setTemplate('application/meus-anuncios/index');
		return $new_model;		
	}

	public function displayimageAction(){
		
		$id_anuncio = $this->params()->fromRoute('id', 0);
		$opcao = $this->params()->fromRoute('cod', 0);
		
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		$records = $this->listimages($sessionLogin['user']->diretorio,$id_anuncio);
		//var_dump($records);exit;
		
		//return $this->response;
		$new_model = new ViewModel(array('dados'=>$records,'id_anuncio'=>$id_anuncio,'opcao'=>$opcao));
		$new_model->setTerminal(true);
		return $new_model;
		
	}
	
	private function listimages($diretorio,$id_anuncio){
		
		$path_folder = "./public/users/".$diretorio.$id_anuncio."/50";
		$path_host = "/users/".$diretorio.$id_anuncio."/50";
		
		
		if(file_exists($path_folder))
		{
			$list_files = scandir($path_folder);
			
			if(!empty($list_files)){
				foreach($list_files as $k => $v){
					if(file_exists($path_folder."/".$v) && $v != "." && $v != ".."){
						$strimgs[$k]['url'] = $path_host."/".$v;
						$strimgs[$k]['nome'] = $v;
						//$strimgs .= "<img src='".$path_host."/".$v."' style='padding:3px;' /><a href=javascript:callAjax('/meus-anuncios/deleteimage/".$id_anuncio."/".$v."',$('#loadfotos".$id_anuncio."'));  ><i class='icon-remove-circle'></i></a> ";
					}
				}
			}
		}
		
		return $strimgs;
		
	}

	public function addimageAction(){
		
		$id_anuncio = $this->params()->fromRoute('id', 0);
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		if(empty($id_anuncio)){
			echo json_encode(array('files'=>array('error'=>'Erro id. Tente Novamente')));
			exit;
		}
		
		$create_folders = array(50,80);
		
		$path_folder = "./public/users/".$sessionLogin['user']->diretorio.$id_anuncio;
		//$path_folder_full = "./public/users/".$sessionLogin['user']->diretorio.$id_anuncio."/50";
		
		if(!empty($create_folders))
		{
			foreach($create_folders as $k => $v){
				
				if(!file_exists($path_folder."/".$v))
				{
					$create_dir = mkdir($path_folder."/".$v,0755,true);
					if(!$create_dir){
						echo json_encode(array('files'=>array('error'=>'Erro ao criar diretório. Tente Novamente')));
						exit;
					}
				}
				
			}
		
			//verifica qtd de imagens

			$list_files = scandir($path_folder."/50");

			if((count($list_files)-2) >= 10){
				echo json_encode(array('files'=>array('error'=>'Maximum of 10 photos')));
				exit;
			}


		}
		
		$file = $this->params()->fromFiles('filesaddfotos');
		//var_dump($id,$path_folder,$file,$file['name'],$_FILES);exit;
		
		$size = new Size(array('min'=>100,'max'=>512000)); //minimum bytes filesize
        $extension = new Extension(array("extension" => array("jpg","gif","png","jpeg")));
        
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
        	//$files_current_count = count(scandir($path_folder,1))-1;
        	$getextension = pathinfo($file['name'], PATHINFO_EXTENSION);
        	$namefile = time().".".$getextension;
        	$current_pathimg = $path_folder.'/'.$namefile;
			
			
			$adapter->setDestination($path_folder);
			$adapter->addFilter('Rename', array('target' => $current_pathimg,'overwrite' => true));
            if ($adapter->receive()) {
            	//cria imagens miniaturas
      			if(!empty($create_folders))
				{
					foreach($create_folders as $k => $v){
						$new_pathimg = $path_folder.'/'.$v.'/'.$namefile;
						$this->geraimagem($current_pathimg, $new_pathimg,$v);
					}
				}
            	echo json_encode(array('files'=>array($file+array('id'=>$id_anuncio,'pathfull'=>$path_folder.'/'.$files_current_count."-".$file['name']))));
			}
		}
		
		//var_dump($error);exit;
		//echo json_encode($_FILES);
		//$viewModel = new ViewModel();
		//$viewModel->setTerminal(true);
		return $this->response;
		//return $viewModel;
		
	}

	public function deleteimageAction(){
		
		$nome_image = $this->params()->fromRoute('cod', 0);
		$id_anuncio = $this->params()->fromRoute('id', 0);
		
		if(empty($nome_image) && empty($id_anuncio)){
			echo json_encode(array('files'=>array('error'=>'Erro name file. Tente Novamente')));
			exit;
		}
		
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		$folders = array(50,80);
		$folders[] = 0;
		
		if(!empty($folders)){
			foreach($folders as $k => $v){
				if(empty($v)){
					$path_folder = "./public/users/".$sessionLogin['user']->diretorio.$id_anuncio;
				}else{
					$path_folder = "./public/users/".$sessionLogin['user']->diretorio.$id_anuncio.'/'.$v;
				}
				$file = $path_folder."/".$nome_image;
				if(file_exists($file))
				{
					$remove_file = unlink($file);
					if(!$remove_file){
						echo json_encode(array('files'=>array('error'=>'Erro ao remover imagem. Tente Novamente')));
						exit;
					}
				}
			}
		}
		
		$records = $this->listimages($sessionLogin['user']->diretorio,$id_anuncio);

		$new_model = new ViewModel(array('dados'=>$records,'id_anuncio'=>$id_anuncio));
		$new_model->setTerminal(true);
		$new_model->setTemplate('application/meus-anuncios/displayimage');
		return $new_model;
		
		//return $this->response;

	}

	public function geraimagem($current_pathimg,$new_pathimg,$larguraMax = null,$alturaMax =null)
	{
		
		$dados_img = getimagesize($current_pathimg);
		
		if(empty($alturaMax) && empty($larguraMax)){
			$larguraMax = 50;
			$alturaMax = $larguraMax;
		}
		
		if(empty($alturaMax)){
			$alturaMax = $larguraMax; 
			
		}elseif(empty($larguraMax)){
			$larguraMax = $alturaMax; 
		}
		
		 // Somente exita números muito pequenos. Para este exemplo não quero
		if($larguraMax < 20)
		    $larguraMax = 20;
		
		
		$largura_original = $dados_img[0];
		$altura_original  = $dados_img[1];
		
		// Calcula a nova altura da imagem 
		$largura_nova = $larguraMax;
		$altura_nova = intval( ( $altura_original * $largura_nova ) / $largura_original );
		
		
		if ($dados_img['mime'] == "image/gif") {
		    $imagem = imagecreatefromgif($current_pathimg );
		} elseif ($dados_img['mime'] == "image/jpeg") {
		    $imagem = imagecreatefromjpeg($current_pathimg );
		} elseif ($dados_img['mime'] == "image/png") {
		    $imagem = imagecreatefrompng($current_pathimg );
		} elseif ($dados_img['mime'] == "image/x-ms-bmp") {
		    //$imagem = imagecreatefromwbmp($filename);
		    $magicianObj = new ZC_ImageLib($current_pathimg);
			$magicianObj->resizeImage($largura_nova, $altura_nova);
			$magicianObj->saveImage($new_pathimg, 100);
			//echo file_get_contents($cache_file);
			exit;
		} else {
		    die("No image support in this PHP server");
		}
		
		$nova_imagem = imagecreatetruecolor( $largura_nova, $altura_nova );
		imagecopyresampled( $nova_imagem, $imagem, 0, 0, 0, 0, $largura_nova, $altura_nova, $largura_original, $altura_original );
		
				
		if ($dados_img['mime'] == "image/gif") {
			imagegif($nova_imagem, $new_pathimg);
		} elseif ($dados_img['mime'] == "image/jpeg") {
			imagejpeg($nova_imagem, $new_pathimg, 100 );
		} elseif ($dados_img['mime'] == "image/png") {
			imagepng($nova_imagem, $new_pathimg);
		} else {
		    die("No image support in this PHP server");
		}
		
		imagedestroy($nova_imagem);
	}

	public function deleteAction()
	{
		$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
		
		$id = $this->params()->fromRoute('id', 0);
		$cod_action = $this->params()->fromRoute('cod', 0);


		$repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
		$check_records = $repository->findByAnuncioAndUser($id,$sessionLogin['user']->id);
		
		if(empty($check_records)){
			return $this->redirect()->toRoute('meus-anuncios');
			exit;
		}

		if($cod_action == 1){
			$service = $this->getServiceLocator()->get("service_meusanuncios");
			
			$set_update['id'] = $id;
			$set_update['status'] = 4;
 			$service->update($set_update);
			
			$repository = $this->getEm()->getRepository("Application\Entity\Usuario");
			$obj_records_user = $repository->findById($sessionLogin['user']->id);
			
			
			if(!empty($obj_records_user)){
				
				$records_user = $obj_records_user->getArrayCopy();
				$service = $this->getServiceLocator()->get("service_register");
				$records_user['qtd_anuncio'] = $records_user['qtd_anuncio'] + 1;
				$service->update($records_user); 	
				$msg = 1;
			}
			
 			//$service->delete($id);
			//return $this->redirect()->toRoute('meus-anuncios');
			$new_model = new ViewModel(array('msg'=>$msg));
			return $new_model;
		}elseif(!empty($id)){
			
			$obj_records = $repository->findByAnuncio($id);
			
			$new_model = new ViewModel(array('dados'=>$obj_records));
			return $new_model;
				
		}
		
		
		
		
	}
    



}
