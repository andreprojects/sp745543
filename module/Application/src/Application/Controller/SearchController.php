<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
 	Zend\View\Renderer\PhpRenderer,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container; 

class SearchController extends AbstractActionController {
    
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
        $word = $this->params('word', 'vendo');

        $validator_alpha = new \Zend\Validator\Alpha(array('allowWhiteSpace' => true));

        if ($validator->isValid($word)) {
            $
        }

        $repository = $this->getEm()->getRepository("Application\Entity\Anuncio");
        $obj_records = $repository->findByWord($word);
        
        var_dump($obj_records);

        $result = new ViewModel(array('form' => $form,'records' => $load_records,'msg' => $msg));
    	//$result->setTerminal(true);
		return $result;   
        //return $this->response;     
    }



}
