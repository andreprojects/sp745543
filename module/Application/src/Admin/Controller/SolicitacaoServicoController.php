<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

class SolicitacaoServicoController extends AbstractActionController {
    
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

    public function finalizaservicoAction()
    {
        $id_plano_anuncio   = $this->params()->fromRoute('id_plano_anuncio', 0);
        $form = $this->getServiceLocator()->get("service_finaliza_servico_form");


        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid() && !empty($id_plano_anuncio)) {
                
                $service = $this->getServiceLocator()->get("service_plano_anuncio");
                $records = $request->getPost()->toArray();


                $records['id'] = $id_plano_anuncio;
                $records['data_inicio'] = new \DateTime($records['data_inicio']);
                $records['data_fim'] = new \DateTime($records['data_fim']);
                $records['data_finalizado'] = new \DateTime("now America/Sao_Paulo");
                $records['status'] = 2;

                //$records['token'] = md5(uniqid(time()));
                //var_dump($records);
                //$service->update($records);

                $repository = $this->getEm()->getRepository("Application\Entity\PlanoAnuncio");
                $obj_records_pa = $repository->findBySolicitacaoWithId($id_plano_anuncio);

                if(!empty($obj_records_pa)){

                    $records_email['email']              = $obj_records_pa['0']['u_email'];
                    $records_email['nome']               = $obj_records_pa['0']['u_nome'];
                    $records_email['records']            = $obj_records_pa['0'];
                    $records_email['cm']['data_inicio']  = $obj_records_pa['0']['pa_data_inicio'];
                    $records_email['cm']['data_fim']     = $obj_records_pa['0']['pa_data_fim'];
                    $records_email['cm']['media_clique'] = $obj_records_pa['0']['pa_media_clique'];
                    $records_email['cm']['posicao_media'] = $obj_records_pa['0']['pa_posicao_media'];
                    $records_email['cm']['impressao']    = $obj_records_pa['0']['pa_impressao'];

                    $records_email['link_renovacao'] = "";
                   
                    $service->setMailSubject($records_email['nome']." sua campanha foi finalizada");
                    $service->setMailTemplate("admin/solicitacao-servico/finaliza-email");
                    $service->SendEmail($records_email);
                }

                
                $msg['ref']     = "servico";
                $msg['tipo']    = "success";    
                $msg['cod_msg'] = "1";
                //$form->setData(array('data_inicio'=>''));
                
                //$service->SendEmail($records);    
                //return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
            }
        }
        
        //$repository = $this->getEm()->getRepository("Application\Entity\Servico");
        //$obj_records = $repository->fetchPairs();
        
        //var_dump($obj_records->getArrayCopy());
        $dados['id_plano_anuncio'] = $id_plano_anuncio;

        $result = new ViewModel(array('form' => $form,'msg' => $msg,'dados'=>$dados));
        $result->setTerminal(true);
        return $result;     
    }

    public function historioAction(){




    }

    public function listAction(){
        //$id_ads   = $this->params()->fromRoute('id_ads', 0);
        $col_order   = $this->params()->fromRoute('col_order', 'pa.id');
        $type_order   = $this->params()->fromRoute('type_order', 'DESC');

        $repository = $this->getEm()->getRepository("Application\Entity\PlanoAnuncio");
        $or_result = $repository->findBySolicitacao($col_order,$type_order);//,array($col_order=>$type_order));
    //var_dump($or_result);
        $page = $this->params()->fromRoute('page',1);
        $paginator = new Paginator(new ArrayAdapter($or_result));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(5);

        
        $result = new ViewModel(array('dados'=>$paginator));
        $result->setTerminal(true);
        return $result;
    }

    public function removeAction(){

        $id_ads   = $this->params()->fromRoute('id_ads', 0);
        $id_pergunta   = $this->params()->fromRoute('id_pergunta', 0);

        //Verificar se a pergunta pertence ao usuário logado

        if(!empty($id_ads)&&!empty($id_pergunta))
        {
            $service = $this->getServiceLocator()->get("service_plano_anuncio");
            $records['id'] = $id_pergunta;
            $records['status'] = 4;
            $service->update($records);

            //return $this->redirect()->toRoute('perguntas',array('id_ads'=>$id_ads));
            return $this->response;

        }

    }

    public function publicaAction(){

        $id_ads   = $this->params()->fromRoute('id_ads', 0);
        $id_plano_anuncio   = $this->params()->fromRoute('id_plano_anuncio', 0);

        //Verificar se a pergunta pertence ao usuário logado

        if(!empty($id_ads)&&!empty($id_plano_anuncio))
        {
            $service = $this->getServiceLocator()->get("service_plano_anuncio");
            $records['id'] = $id_plano_anuncio;
            $records['data_alteracao'] = new \DateTime("now America/Sao_Paulo");
            $records['status'] = 1;
            $service->update($records);

            //return $this->redirect()->toRoute('perguntas',array('id_ads'=>$id_ads));
            return $this->response;

        }

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

    public function emailauthAction()
    {
        //$sessionLogin = $this->getServiceLocator()->get("service_helper_session_login");
        //Verificar se já está cadastrado
        $token    = $this->params('token', false);
        $email    = $this->params('email', false);
        $id_anuncio  = $this->params('id_anuncio', false);
        
        $repository  = $this->getEm()->getRepository("Application\Entity\Usuario");
        $obj_records = $repository->findByTokenAndEmail($token,$email);
        //var_dump($obj_records);exit;

        if(!empty($obj_records)){
                        
            $auth = new AuthenticationService;

            $sessionStorage = new SessionStorage("Login");
            $auth->setStorage($sessionStorage);
            
            //$email = "andrework@gmail.com";
            //$records['senha'] = "0ab478795daa5b428b51e548a69414f94a4da5380ae0cd92198694afc52bd0bb58347762a4037efce9d8b03736aa2250dce454706346893e0ed0b388f13f17d0";
            
            //$service = $this->getServiceLocator()->get("service_changepassword");
            $authAdapter = $this->getServiceLocator()->get('Login\Auth\Adapter');
            $authAdapter->setUsername($obj_records->email)
                        ->setPassword($obj_records->senha);
            
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $getIdentity = $result->getIdentity();
                $getIdentity['user']->senha = null;
                $session = new Container('user');
                $session->offsetSet('credito', $getIdentity['user']->credito);
                    
                return $this->redirect()->toRoute('perguntas',array('id_ads'=>$id_anuncio,'id_pergunta'=>$id_pergunta));
                
            }else{
                return $this->redirect()->toRoute('home-message');
            }
        }

        //$result = new ViewModel(array('form' => $form,'records' => $load_records,'msg' => $msg));
        //$result->setTerminal(true);
        return $this->response;     
    }
    



}
