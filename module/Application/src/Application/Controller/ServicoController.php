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
        
        $id_anuncio = $this->params()->fromRoute('id_anuncio', 0);

        $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_records = $repository->findById($id_anuncio);

         $result = new ViewModel(array('dados' => $obj_records));
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
