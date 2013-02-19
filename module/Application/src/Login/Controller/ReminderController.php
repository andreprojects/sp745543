<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\ReminderForm;

class ReminderController extends AbstractActionController {
    
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
        $form = $this->getServiceLocator()->get("service_reminder_form");
        $request = $this->getRequest();
        $msg=false;
        
        if ($request->isPost()) {
            $obj_post = $request->getPost();
            $form->setData($obj_post);
            if ($form->isValid()) {
              //convert to array
              $obj_post_array = $obj_post->toArray();
                            
              $repository = $this->getEm()->getRepository("Application\Entity\Users");
              $obj_records_users = $repository->findByEmail($obj_post_array['email']);
              $records = $obj_records_users->getArrayCopy();
              if(empty($records['senha'])){
                //reenvia email para confirmar email
                $service = $this->getServiceLocator()->get("service_register");
                $service->SendEmail($records);
                $msg=2;
              }else{
                //var_dump($records);exit;
                $service = $this->getServiceLocator()->get("service_reminder");
                $service->SendEmail($records);
                $msg = 1;
               
              }
            
            }
        }
		
		$new_model = new ViewModel(array('form' => $form,'msg'=>$msg));
		$new_model->setTerminal(true);
		$new_model->setTemplate('login/reminder/index');
		return $new_model;

        //return new ViewModel(array('form' => $form,'msg'=>$msg));    

    }
    
    public function viewtplAction(){
        
        $records['token'] = md5(uniqid(time()));
        $records['nome'] = "teste";
        $records['email'] = "andre@xxx.com";
        $new_model = new ViewModel(array('dados'=>$records));
        $new_model->setTemplate('login/reminder/reminder-password');
        return $new_model;
        //$tpl = $this->emailRenderer->render($new_model);
        //echo "test";
    }
    


}
