<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;

class PerfilController extends AbstractActionController {
    
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

        $msg = array();
        //$this->params()->fromRoute('action', 0);
        $username = $this->params()->fromRoute('username', 0);

        if(empty($username)){
          return $this->redirect()->toRoute("home-message");
        }

        $dados['username'] = $username;
        return new ViewModel(array('form' => $form,'msg' => $msg,'dados' => $dados));    

    }

    public function aAction(){

      $msg = array();
      //$this->params()->fromRoute('action', 0);
      $username = $this->params()->fromRoute('username', 0);
      $id_ads = $this->params()->fromRoute('id_ads', 0);
      $url_ads = $this->params()->fromRoute('url_ads', 0);

      if(empty($username)){
        return $this->redirect()->toRoute("home-message");
      }

      $dados['username'] = $username;
      $dados['url_ads'] = $url_ads;
      return new ViewModel(array('form' => $form,'msg' => $msg,'dados' => $dados));

    }

  public function displayimageAction(){
    
    $id_anuncio = $this->params()->fromRoute('id', 0);
    $sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
    
    echo $this->listimages($sessionLogin['user']->diretorio,$id_anuncio);
    
    //var_dump($list_files);
    
    return $this->response;
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
            $strimgs .= "<img src='".$path_host."/".$v."' style='padding:3px;' /><a href=javascript:callAjax('/meus-anuncios/deleteimage/".$id_anuncio."/".$v."',$('#loadfotos".$id_anuncio."'));  ><i class='icon-remove-circle'></i></a> ";
          }
        }
      }
    }
    
    return $strimgs;
    
  }
    


}
