<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;
	
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

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
        //Verificar se a pergunta pertence ao usuário

        $id_ads   = $this->params()->fromRoute('id_ads', 0);

        $repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
        $or_pergunta = $repository->findByIdAds($id_ads);


        $result = new ViewModel(array('form' => $form,'dados'=>$or_pergunta));
        //$result->setTerminal(true);
        return $result;

    }


    public function respostaperguntaAction(){
        //Verificar se a pergunta pertence ao usuário

        $id_pergunta   = $this->params()->fromRoute('id_pergunta', 0);

        $form = $this->getServiceLocator()->get("resposta_pergunta_form");


        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid() && !empty($id_ads)) {

                $service = $this->getServiceLocator()->get("service_pergunta");
                $records = $request->getPost()->toArray();

                $records['id'] = $id_pergunta;

                $service->update($records);

                //$repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
                //$or_pergunta = $repository->findByIdAds($id_ads);
            }

        }

        $repository = $this->getEm()->getRepository("Application\Entity\Pergunta");
        $or_pergunta = $repository->findById($id_pergunta);

        $result = new ViewModel(array('form' => $form,'dados'=>$or_pergunta));
        //$result->setTerminal(true);
        return $result;

    }

}
