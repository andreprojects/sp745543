<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container; 

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

class ServicoController extends AbstractActionController {
    
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
    	//$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        //$dados['word'] = $this->params()->fromRoute('word', 0);
        $request = $this->getRequest()->getPost();

        if(!empty($request)){
            $post = $request->toArray();
        }
        //var_dump($post);
        $result = new ViewModel(array('dados' => $post));
    	//$result->setTerminal(true);
		return $result;   
        //return $this->response;     
    }

    public function adwordsAction(){

        $sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
       
        $id_anuncio = $this->params()->fromRoute('id_anuncio', 0);

        $repository_ads = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_ads = $repository_ads->findByUserWithAds($id_anuncio);

        //verifica se o anuncio pertence ao usuario

        if(!empty($obj_ads)){
            if($obj_ads[1]->id != $sessionLogin['user']->id){
                return $this->redirect()->toRoute('meus-anuncios');
            }
        }else{
            return $this->redirect()->toRoute('meus-anuncios');
        }

        //verifica se o servico ja foi ativado
        $repository_plano_ads = $this->getEm()->getRepository("Application\Entity\PlanoAnuncio");
        $obj_plano_ads = $repository_plano_ads->findByAnuncio($id_anuncio);

        if(!empty($obj_plano_ads)){
            //return $this->redirect()->toRoute('meus-anuncios');
            $msg = 2;
        }

        $form = $this->getServiceLocator()->get("servico_plano_anuncio_form");

        $request = $this->getRequest();

        if ($request->isPost()) {
            //var_dump($request->getPost());
            $form->setData($request->getPost());

            if ($form->isValid() && empty($msg)) {

                $service = $this->getServiceLocator()->get("service_plano_anuncio");
                $records = $request->getPost()->toArray();

                if(!empty($records['tipo']) && !empty($records['plano'])){

                    /*preco temporario*/
                    switch ($records['plano']) {
                        case '1':
                            $valor_plano = 40;
                            break;
                        
                        case '2':
                            $valor_plano = 80;
                        break;
                        
                        default:
                            return $this->redirect()->toRoute('meus-anuncios');
                        break;
                    }

                    //verifica se credito do usuario é maior que valor do plano
                    if($valor_plano > $obj_ads[1]->credito){
                        $msg = 4;
                    }else{

                        $records['id_plano'] = $records['plano'];
                        $records['id_anuncio'] = $id_anuncio;
                        $records['status'] = 0;

                        if($records['tipo'] == 3){
                            $records['url_site'] = $records['site'];
                        }

                        $service->insert($records);

                        $service_user = $this->getServiceLocator()->get("service_register");
                        $records_user['id'] = $sessionLogin['user']->id;
                        $records_user['credito'] = $obj_ads[1]->credito-$valor_plano;
                        $service_user->update($records_user);
                        //atualizar novo credito na sessão

                        $msg = 1;
                    }


                }else{
                    $msg = 3;
                }

            }


        }




        $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_records = $repository->findByUserWithAds($id_anuncio);

         $result = new ViewModel(array('form' => $form,'dados' => $obj_records,'msg'=>$msg));
        //$result->setTerminal(true);
        return $result;   

    }


    public function listAction()
    {
        //$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");

        /*$validator_alpha = new \Zend\Validator\Alpha(array('allowWhiteSpace' => true));

        if (!$validator->isValid(trim(strip_tags($word)))) {
            $msg['error'] = 1;
        }else{*/

        $col_order   = $this->params()->fromRoute('col_order', 'id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');
        
        $word = $this->params('word', 'casa');
        $word = trim(strip_tags($word));
        $word = $this->textoURL($word,'%');

        $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_records = $repository->findByWord($word,$col_order,$type_order);

        //var_dump($obj_records);exit;

        if(!empty($obj_records))
        {
            $ar_full = $obj_records;
            //$ar_full = $obj_records[0]->getArrayCopy()+$obj_records[1]->getArrayCopy();
        
            $page = $this->params()->fromRoute('page');
            $paginator = new Paginator(new ArrayAdapter($ar_full));
            $paginator->setCurrentPageNumber($page);
            $paginator->setDefaultItemCountPerPage(2);
        }
        //var_dump($paginator);

        $result = new ViewModel(array('dados' => $paginator,'msg' => $msg));
        $result->setTerminal(true);
        return $result;   
        //return $this->response;     
    }



}
