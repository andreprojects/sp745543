<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

class PerguntaController extends AbstractActionController {
    
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
    	$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        $form = $this->getServiceLocator()->get("pergunta_publica_form");

        $id_ads   = $this->params()->fromRoute('id_ads', 0);

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid() && !empty($id_ads)) {
            	
                $service = $this->getServiceLocator()->get("service_pergunta");
                $records = $request->getPost()->toArray();
                $records['id_anuncio'] = $id_ads;

                $service->insert($records);

                //$records['id_usuario'] = $sessionLogin['user']->id;
                //var_dump($records);

                $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
                $obj_records = $repository->findByUserWithAds($records['id_anuncio']);
                
                if($obj_records){
                    $records_email['nome_remetente'] = $records['nome'];
                    $records_email['email']          = $obj_records['1']->email;
                    $records_email['nome']           = $obj_records['1']->nome;
                    $records_email['titulo']         = $obj_records['0']->titulo;
                    $records_email['msg_pergunta']   = $records['msg_pergunta'];
                
                    $service->setMailSubject($records_email['nome_remetente']." enviou uma pergunta para seu anúncio");
                    $service->SendEmail($records_email);
                }
				
				$form->setData(array('nome'=>'','email'=>'','msg_pergunta'=>''));
				
                    //$service->SendEmail($records);    
    				//return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
                
            }
        }

        $result = new ViewModel(array('form_pergunta' => $form,'msg' => $msg,'id_anuncio'=>$id_ads));
    	$result->setTerminal(true);
		return $result;     
    }

    public function listaperguntaAction(){
        //Verificar se a pergunta pertence ao usuário -ok
        $sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");

        $id_ads   = $this->params()->fromRoute('id_ads', 0);

        $repository_ads = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $or_anuncio = $repository_ads->findByAnuncioAndUser($id_ads,$sessionLogin['user']->id);

        if(empty($or_anuncio)){
            return $this->redirect()->toRoute('meus-anuncios');
        }else{
            $ar_anuncio = $or_anuncio->getArrayCopy();
            $ar_anuncio['username'] = $sessionLogin['user']->username;
        }

        //var_dump($or_anuncio);
        $result = new ViewModel(array('form' => $form,'titulo'=>$or_anuncio->titulo,'ads'=>$ar_anuncio));
        //$result->setTerminal(true);
        return $result;

    }

    public function listaperguntaconteudoAction(){

        $sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");

        $id_ads   = $this->params()->fromRoute('id_ads', 0);
        $col_order   = $this->params()->fromRoute('col_order', 'id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');


        $repository_ads = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $or_anuncio = $repository_ads->findByAnuncioAndUser($id_ads,$sessionLogin['user']->id);
        //var_dump($id_ads,$sessionLogin['user']->id);exit;
        if(empty($or_anuncio)){
            return $this->redirect()->toRoute('meus-anuncios');
        }else{
            $ar_anuncio = $or_anuncio->getArrayCopy();
            $ar_anuncio['username'] = $sessionLogin['user']->username;
        }
        //var_dump(array($col_order=>$type_order));
        $repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
        $or_pergunta = $repository->findByIdAds($id_ads,array($col_order=>$type_order));

        $page = $this->params()->fromRoute('page',1);
        $paginator = new Paginator(new ArrayAdapter($or_pergunta));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(2);

        
        $result = new ViewModel(array('form' => $form,
                                        'dados'=>$paginator,
                                        'titulo'=>$or_anuncio->titulo,
                                        'ads'=>$ar_anuncio));
        $result->setTerminal(true);
        return $result;

    }

    public function removeAction(){

        $id_ads   = $this->params()->fromRoute('id_ads', 0);
        $id_pergunta   = $this->params()->fromRoute('id_pergunta', 0);

        //Verificar se a pergunta pertence ao usuário logado

        if(!empty($id_ads)&&!empty($id_pergunta))
        {
            $service = $this->getServiceLocator()->get("service_pergunta");
            $records['id'] = $id_pergunta;
            $records['status'] = 2;
            $service->update($records);

            return $this->redirect()->toRoute('perguntas',array('id_ads'=>$id_ads));

        }

    }

    public function publicaAction(){

        $id_ads   = $this->params()->fromRoute('id_ads', 0);
        $id_pergunta   = $this->params()->fromRoute('id_pergunta', 0);

        //Verificar se a pergunta pertence ao usuário logado

        if(!empty($id_ads)&&!empty($id_pergunta))
        {
            $service = $this->getServiceLocator()->get("service_pergunta");
            $records['id'] = $id_pergunta;
            $records['status'] = 1;
            $service->update($records);

            return $this->redirect()->toRoute('perguntas',array('id_ads'=>$id_ads));

        }

    }


    public function respostaperguntaAction(){
        //Verificar se a pergunta pertence ao usuário logado
        $sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");

        $id_pergunta   = $this->params()->fromRoute('id_pergunta', 0);
        $tipo   = $this->params()->fromRoute('tipo', 0);

        $form = $this->getServiceLocator()->get("resposta_pergunta_form");

        $repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
        $or_pergunta = $repository->findById($id_pergunta);

        $repository_ads = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $or_ads = $repository_ads->findByAnuncio($or_pergunta[0]->id_anuncio);

        if($or_ads->id_usuario != $sessionLogin['user']->id){
            return $this->redirect()->toRoute('meus-anuncios');
        }

        if($tipo == 1){
            $form->setData(array('msg_resposta'=>$or_pergunta[0]->msg_resposta));
        }

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid() && !empty($id_pergunta)) {

                $service = $this->getServiceLocator()->get("service_pergunta");
                $records = $request->getPost()->toArray();

                $records['id'] = $id_pergunta;
                $records['status'] = 1;
                if($tipo != 1){
                    $date = new \DateTime("now America/Sao_Paulo");
                    $records['data_resposta'] = $date;
                    
                }
                $service->update($records);
                $msg['tipo'] = "success";
                if($tipo != 1){
                    $msg['cod_msg'] = "1";
                }else{
                    $msg['cod_msg'] = "2";
                }      
                $form->setData(array('msg_resposta'=>''));

                //$repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
                //$or_pergunta = $repository->findByIdAds($id_ads);
            }

        }

        $result = new ViewModel(array('form' => $form,'dados'=>$or_pergunta,'tipo'=>$tipo,'msg'=>$msg));
        $result->setTerminal(true);
        return $result;

    }

}
