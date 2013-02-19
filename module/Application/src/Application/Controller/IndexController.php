<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class IndexController extends AbstractActionController {
    
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
    
    public function indexAction() {
		
        $referencia = $this->params()->fromRoute('ref', 0);
		$tipo 		= $this->params()->fromRoute('tipo', 0);
		$cod_msg 	= $this->params()->fromRoute('cod_msg', 0);

		$records[$tipo]['ref'] 	   = $this->params()->fromRoute('ref', 0);	
		$records[$tipo]['cod_msg'] = $this->params()->fromRoute('cod_msg', 0);
			
        /** Zend\DB
          $categoriaService = $this->getServiceLocator()->get("Livraria\Model\CategoriaService");
          $categorias = $categoriaService->fetchAll();
         */
         
       	 
        
        
        $repository = $this->getEm()->getRepository("Application\Entity\Areas");
        $array_records = $repository->fetchPairs();
		$qtd_comprados = $repository->countAll();
       	
		$session = new Container('ganhe');
		$session->offsetSet('qtd_compradas', $qtd_comprados);
		$session->offsetSet('qtd_limite', 650);
		$qtd_limite = $session->offsetGet('qtd_limite');
		$qtd_livre = $qtd_limite- $qtd_comprados;
		$session->offsetSet('qtd_livres', $qtd_livre);
		$valor = ($qtd_livre*3);
		$valor_atual = "R$ ".number_format($valor, 2, ',', '.');
		$session->offsetSet('valor_atual', $valor_atual);
		
         

        return new ViewModel(array('dados' => $records,'array_records_all' => $array_records));
    }
	
	public function listAreasAction() {
		$result = new ViewModel();
    	$result->setTerminal(true);
        $repository = $this->getEm()->getRepository("Application\Entity\Areas");
        $array_records = $repository->fetchPairs();
		
		$result->setVariables(array('dados' => $records,'array_records_all' => $array_records));
    	return $result;
        //return new ViewModel(array('dados' => $records,'array_records_all' => $array_records));
    }
	
	public function addAction(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$coord = base64_decode(urldecode($area));
		$ar_coord_g = explode(",",$coord);
    	$id_area = str_replace(",","",$coord);
		
		$repository = $this->getEm()->getRepository("Application\Entity\Areas");
		$obj_records = $repository->findByArea($ar_coord_g[0],$ar_coord_g[1],$ar_coord_g[2],$ar_coord_g[3]);
		
        if(!empty($obj_records))
        {
        	//$records = $obj_records->getArrayCopy();
        	return $this->redirect()->toRoute('home-message',array('tipo'=>'falert','ref'=>'add','cod_msg'=>'2'));
		}
		
		$sessionUser = new Container('user');
		$credito = $sessionUser->offsetGet('credito');
		
		$session = new Container('carrinho');
		$valor_total_atual = (float) $session->offsetGet('vl_total');
		
		if(empty($credito) || $credito < 3 || $valor_total_atual >= $credito)
		{
			return $this->redirect()->toRoute('home-message',array('tipo'=>'falert','ref'=>'add','cod_msg'=>'1'));   
		}else{
			
			$qtd = $session->offsetGet('qtd') + 1;
			$session->offsetSet('qtd', $qtd);
			$valor_total = (3 * $qtd);
			$session->offsetSet('vl_total', $valor_total);
			
			$areas = $session->offsetGet('areas');
			$areas[] = array('id_area' => $id_area,'coord' => $coord,'valor' => 3);
			$session->offsetSet('areas', $areas);
			
			return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'add','cod_msg'=>'1'));
			
		}
		
	}

	public function removeAction(){
		

		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		if(!$sessionLogin){
			 return $this->redirect()->toRoute("home");
		}
		
		$area = $this->params()->fromRoute('area', 0);
		
		$coord = base64_decode(urldecode($area));
    	$id_area = str_replace(",","",$coord);
		
		$session = new Container('carrinho');
		$areas = $session->offsetGet('areas');
		
		if(!empty($areas)){
			foreach($areas as $k => $v){
				if($id_area == $v['id_area']){
	          	  unset($areas[$k]);
				  $session->offsetSet('areas', $areas);
		          
		          $qtd = $session->offsetGet('qtd') - 1;
				  $session->offsetSet('qtd', $qtd);
				  
				  $vl_total = $session->offsetGet('vl_total') - 3;
				  $session->offsetSet('vl_total', $vl_total);
				  
				  return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'add','cod_msg'=>'2'));

	        	}
			}
		}
		
	}

	public function registerSessionAction(){
		
		$sm = $this->getEvent()->getApplication()->getServiceManager();
		$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
		$sessionLogin = $helper('Login');

		
		$session = new Container('user');
		$session->offsetSet('credito', $sessionLogin['user']->credito);
		return $this->redirect()->toRoute("home");
		
	}
    
    public function loginIframeAction(){

		$new_model = new ViewModel();
		$new_model->setTerminal(true);
		$new_model->setTemplate('application/index/login-iframe');
        return $new_model;
	    
    }
    
    public function photoSpaceAction(){
		
		//$sessionLogin = $this->_helper->UserIdentity('Login');
		
		$tipo 		= $this->params()->fromRoute('tipo', 0);
		
		$background = imagecreatefromjpeg("./data/images/spacefree.jpg");
		//$response = $this->getResponse();
		
		if($tipo != 2){
		
			//$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
			$sm = $this->getEvent()->getApplication()->getServiceManager();
			$helper = $sm->get('viewhelpermanager')->get('UserIdentity');
			$sessionLogin = $helper('Login');
			//var_dump($sessionLogin);exit;
			
			if(!empty($sessionLogin)){
			
				$repository = $this->getEm()->getRepository("Application\Entity\Areas");
				$obj_records = $repository->findByUser($sessionLogin['user']->id);
				
				$my_image = imagecreatefromjpeg("./public/images/app/m51_minhas.jpg");
	  			$imagesx = imagesx($my_image);
	  			$imagesy = imagesy($my_image);
				
				$add_carrinho = imagecreatefromjpeg("./public/images/app/m51_carrinho.jpg");
				
				if(!empty($obj_records)){
					//$array_records = $obj_records->getArrayCopy();
					foreach($obj_records as $item){
						imagecopymerge($background, $my_image,$item->p_left,$item->p_top,0,0,$imagesx,$imagesy,100);	
					}
					
				}
				
				$session = new Container('carrinho');
				$areas = $session->offsetGet('areas');
				if(!empty($areas)){
					foreach($areas as $k=>$v){
      					$v_coord = explode(",",$v['coord']);
      					imagecopymerge($background, $add_carrinho,$v_coord[0],$v_coord[1],0,0,imagesx($add_carrinho),imagesy($add_carrinho),100);
   		 			}
				}
				
			}
	        
	        //$response->getHeaders()->addHeaderLine('Content-Type', "image/jpg");
		
		}else{
			//$response->getHeaders()->addHeaderLine('Cache-Control',"no-cache, must-revalidate");
			
		}
        //var_dump($response->getHeaders());
        //header("Cache-Control: no-cache, must-revalidate");
        header("Content-type: image/jpeg");
        
        imagejpeg($background,null,100);
        imagedestroy($background);
        
        //return $response;
        
    }

}
