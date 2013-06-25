<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;


use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

use Aws\S3\S3Client;
use Aws\Common\Enum\Region;
//use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;
use Guzzle\Http\EntityBody;

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

       // $msg = array();
        //$this->params()->fromRoute('action', 0);
        $username = $this->params()->fromRoute('username', 0);

        if(empty($username)){
          return $this->redirect()->toRoute("home-message");
        }

        
        $repository = $this->getEm()->getRepository("Application\Entity\Usuario");
        $obj_records = $repository->findByUsername($username);

        $dados['id_usuario'] = $obj_records->id;
        $dados['username'] = $username;
        return new ViewModel(array('dados' => $dados));    

    }

    public function listadsAction(){
        //$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        //sem auth
        $username  = $this->params()->fromRoute('username', 0);
        $id_usuario  = $this->params()->fromRoute('id_usuario', 0);
        $col_order   = $this->params()->fromRoute('col_order', 'id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');

        $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_records = $repository->findByUserListAll($id_usuario,$col_order,$type_order);



        $page = $this->params()->fromRoute('page');
        $paginator = new Paginator(new ArrayAdapter($obj_records));
            $paginator->setCurrentPageNumber($page);
            $paginator->setDefaultItemCountPerPage(2);

          
        $result = new ViewModel(array(
                      'username' =>  $username,
                      'dados'=>$paginator,
                      )); 
        
        $result->setTerminal(true);
        return $result;
    }

    public function aAction(){

      $msg = array();
      //$this->params()->fromRoute('action', 0);
      $username = $this->params()->fromRoute('username', 0);
      $id_ads   = $this->params()->fromRoute('id_ads', 0);
      $url_ads  = $this->params()->fromRoute('url_ads', 0);

      if(empty($username) || empty($id_ads) ){
        return $this->redirect()->toRoute("home-message");
      }

      $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
    
      $obj_repo = $repository->findByUserWithAds($id_ads);

      if(!empty($obj_repo))
      {
          //$records = new \Doctrine\Common\Collections\ArrayCollection($obj_repo);
          //var_dump($obj_repo['1']->diretorio);exit;

          /*$path_host = "/users/".$obj_repo['1']->diretorio.$id_ads;
          $path_folder = "./public".$path_host;

          if(file_exists($path_folder))
          {
            $list_files = scandir($path_folder);
            
            if(!empty($list_files)){
              foreach($list_files as $k => $v){
                if(file_exists($path_folder."/".$v) && $v != "." && $v != ".."){
                  $notisdir = strstr($v, '.');
                  if(!empty($notisdir)){
                    $collections_images[$k]['50'] = $path_host."/50/".$v;
                    $collections_images[$k]['full'] = $path_host."/".$v; 
                  }
                }
              }
              $dados['collections_images'] = $collections_images;
            }
          }*/

        $aws    = $this->getServiceLocator()->get('aws');
        $s3 = $aws->get('s3');
        $bucket= 'shareplaque-images';

        $path_host = $obj_repo['1']->diretorio."ads/50/".$id_ads;

        try {
                $responseS3= $s3->listObjects(array('Bucket' => $bucket,'Prefix' => $path_host.'/'));
                if(!empty($responseS3)){
                  $data_responseS3 = $responseS3->toArray();
                  if(!empty($data_responseS3['Contents'])){
                    //var_dump($data_responseS3['Contents']);
                    foreach($data_responseS3['Contents'] as $k => $item){
                      if(!empty($item['Size'])){
                        $collections_images[$k]['50'] = "https://shareplaque-images.s3.amazonaws.com/".$item['Key'];
                        $collections_images[$k]['full'] = str_replace("/50/", "/real/","https://shareplaque-images.s3.amazonaws.com/".$item['Key'] );
                      }
                    }
                    
                  } 
                }
                
          } catch (S3Exception $e) {
              echo "There was an error uploading the file.\n ".$e->getMessage();
              exit;
          }

          $dados['collections_images'] = $collections_images;

          $dados['id_ads'] = $id_ads;
          $dados['titulo'] = $obj_repo['0']->titulo;
          $dados['descricao'] = $obj_repo['0']->descricao;

          //$form_pergunta = $this->getServiceLocator()->get("pergunta_publica_form");

      }

      //var_dump($collections_images);

      $dados['username'] = $username;
      $dados['url_ads'] = $url_ads;

      return new ViewModel(array('msg' => $msg,'dados' => $dados));

  }
/*
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
    
  }*/
    


}
