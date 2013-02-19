<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

use Zend\Session\Container;

class AreaController extends AbstractActionController {
    
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
    
    public function step2Action() {
        $sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
        
		$area = $this->params()->fromRoute('area', 0);
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		if(!file_exists($path_img)){
			return $this->redirect()->toRoute("area-edit",array('area'=>$area));
		}
		
		$records['area'] = $area; 
		list($records['width'], $records['height'], $records['type'], $records['attr']) = getimagesize($path_img);
        return new ViewModel(array('dados' => $records));
    }
	
	public function step3Action(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		
		$area_sel = $this->params()->fromRoute('area-sel', 0);
		$area 	  = $this->params()->fromRoute('area', 0);
		
		if(!empty($area_sel)){
			$coord = base64_decode(urldecode($area_sel));
	    	$ar_coord = explode(",",$coord);
			
	   	 	$area_size_default = 10;
	    	list($width, $height) = getimagesize($path_img);
	   	 	$image_p = imagecreatetruecolor($area_size_default,$area_size_default);

	    	$image = @imagecreatefromjpeg($path_img);
			
			if(!$image){
				return $this->redirect()->toRoute('home-message',array('tipo'=>'falert','ref'=>'step3','cod_msg'=>'1'));
                exit;
			}

			$name_submetida_100p = "10x10.jpg";
			
			imagecopy($image_p, $image, 0, 0, $ar_coord[0], $ar_coord[1], $width, $height);
	    	imagejpeg($image_p, $path_folder."/".$name_submetida_100p, 100);
	    	imagedestroy($image_p);
		}
		
		$file_save = $ar_coord[0].$ar_coord[1].$ar_coord[2].$ar_coord[3].".jpg";
		
		$coord_g = base64_decode(urldecode($area));
    	$ar_coord_g = explode(",",$coord_g);
		
		$form = $this->getServiceLocator()->get("service_area_step3_form");
        $request = $this->getRequest();
		
		$repository = $this->getEm()->getRepository("Application\Entity\Areas");
		$obj_records = $repository->findByArea($ar_coord_g[0],$ar_coord_g[1],$ar_coord_g[2],$ar_coord_g[3]);

		$sessionFields 	= new Container('fields');
		
        if(!empty($obj_records))
        {
        	$records = $obj_records->getArrayCopy();
			
			if(empty($records['titulo']) && empty($records['url']) && empty($area_sel)){
				return $this->redirect()->toRoute("area-edit",array('area'=>$area,'action'=>'step2'));
				exit;
			}
			
			if(empty($records['titulo']) && empty($records['url'])){
				
				$records['titulo'] 	= $sessionFields->offsetGet('titulo');
				$records['url']		= $sessionFields->offsetGet('url');
			}
			
        	$form->setData($records);
		}
		
		$uri = $this->getRequest()->getUri();
    	$url_base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
		
        if ($request->isPost()) {
        	$form->setData($request->getPost());
            if ($form->isValid()) {
            		
				$sessionFields->offsetSet('titulo',$request->getPost()->titulo);
				$sessionFields->offsetSet('url',$request->getPost()->url);
            	
				if(!empty($ar_coord))
				{
					$area_file_save = $ar_coord_g[0].$ar_coord_g[1].$ar_coord_g[2].$ar_coord_g[3].".jpg";
					
					if(copy($path_folder."/".$name_submetida_100p,$path_folder."/".$area_file_save)){
				    		
				    	$background = imagecreatefromjpeg("./data/images/spacefree.jpg");
				      
				    	$my_image = imagecreatefromjpeg($path_folder."/".$name_submetida_100p);
				    	$imagesx = imagesx($my_image);
				    	$imagesy = imagesy($my_image);
				      
				    	imagecopymerge($background, $my_image,$ar_coord_g[0],$ar_coord_g[1],0,0,$imagesx,$imagesy,100);
				    	imagejpeg($background,"./data/images/spacefree.jpg",100);
				      	
				      	//remove a imagem selecionada
				      	unlink($path_folder."/".$name_submetida_100p);
				      	imagedestroy($background);
				    }
				
				}
				
                
                $service = $this->getServiceLocator()->get("service_area_step3");
                $records = $request->getPost()->toArray();
                //$service->insert($records);
                $service->update($records);   
				return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'step3','cod_msg'=>'1'));
                
            }
        }
		
		
		if(!empty($ar_coord))
		{
			
			if(file_exists($path_folder."/".$file_save))
			{
				$img_atual = $ar_coord[0].$ar_coord[1].$ar_coord[2].$ar_coord[3]; 
			}else{
				$img_atual = 'my';
			}
			
			$records['img_atual'] = $img_atual;
		
		}else{
			$records['img_atual'] 	 = $ar_coord_g[0].$ar_coord_g[1].$ar_coord_g[2].$ar_coord_g[3];
			$records['modo_sem_sel'] = true;	
		}
		$records['area'] = $area;
		
		return new ViewModel(array('dados' => $records,'form' => $form));
	}
	
	public function rmimgAction(){
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		$path_img = $path_folder."/myImgToCut.jpg";
		
		if(unlink($path_img)){
			return $this->redirect()->toRoute("area-edit",array('area'=>$area));
		}
		
	}
	

	public function myimgAction(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if($sessionLogin){
			$response = $this->getResponse();
        	$response->getHeaders()->addHeaderLine('Content-Type', "image/jpeg");
			
			$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
			
			$img_sel = $this->params()->fromRoute('img-sel', 0);
		
			if(empty($img_sel)){
				$name_file = "myImgToCut.jpg";
			}else{
				$name_file = $img_sel.".jpg";
			}

			$imagegetcontent = @file_get_contents($path_folder."/".$name_file);
			$response->setStatusCode(200);
            $response->setContent($imagegetcontent);
				
			return $response;
		}
	}

	
	public function indexAction()
    {
    	
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$path_folder = "./data/".$sessionLogin['user']->diretorio."images";
		
		//verifica se diretorio exists
		if(!file_exists($path_folder))
		{
			$create_dir = mkdir($path_folder,0755,true);
			if(!$create_dir)
				return $this->redirect()->toRoute("home");
		}elseif(file_exists($path_folder."/myImgToCut.jpg")){
			return $this->redirect()->toRoute("area-edit",array('area'=>$area,'action'=>'step2'));
		}

		$form = $this->getServiceLocator()->get("service_area_upload_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            //$profile = new Profile();
            //$form->setInputFilter($profile->getInputFilter());
            
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                 $nonFile,
                 array('fileupload'=> $File['name'])
             );
			 
            //set data post and file ...    
            $form->setData($data);
             
            if ($form->isValid()) {
                
                $size = new Size(array('min'=>100,'max'=>512000)); //minimum bytes filesize
                $extension = new Extension(array("extension" => array("jpg")));
                
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                $adapter->setValidators(array($size,$extension), $File['name']);
				
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
					
                    $form->setMessages(array('fileupload'=>$error ));
                } else {
                	/*
					$image = imagecreatefrompng($filePath);
					$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
					imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
					imagealphablending($bg, TRUE);
					imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image))
					imagedestroy($image);
					imagejpeg($bg, $filePath . ".jpg", 50);// the 50 is to set the quality, 0 = worst-smaller file, 100 = better-bigger file 
					ImageDestroy($bg);
                	*/
                	
                    $adapter->setDestination($path_folder);
					$adapter->addFilter('Rename', array('target' => $path_folder.'/myImgToCut.jpg',
                     'overwrite' => true));
                    if ($adapter->receive()) {
                        return $this->redirect()->toRoute("area-edit",array('area'=>$area,'action'=>'step2'));
                        //$profile->exchangeArray($form->getData());
                        //echo 'Profile Name '.$profile->profilename.' upload '.$profile->fileupload;
                    }
                }  
            }
        }
         
        return array('form' => $form);
    }

}
