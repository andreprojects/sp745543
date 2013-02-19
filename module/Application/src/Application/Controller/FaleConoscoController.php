<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FaleConoscoController extends AbstractActionController {
    
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

        
        $form = $this->getServiceLocator()->get("service_faleconosco_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                
                $service = $this->getServiceLocator()->get("service_faleconosco");
                $records = $request->getPost()->toArray();
                //$service->insert($records);
                $service->SendEmail($records);    
                return $this->redirect()->toRoute('home-message',array('msg_id'=>'13'));
            }
        }

        return new ViewModel(array('form' => $form));
    }


}
