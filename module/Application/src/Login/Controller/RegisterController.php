<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\RegisterForm;

class RegisterController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;

    public function indexAction()
    {
        /*
         *$uri = $this->getRequest()->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        $base = sprintf('%s://%s', $scheme, $host);
         *
         */

        $form = $this->getServiceLocator()->get("service_register_form");
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get("service_register");
                $records = $request->getPost()->toArray();
                $records['token'] = md5(uniqid(time()));
                
                //var_dump($records);
                $service->insert($records);
                $service->SendEmail($records);    
                //return $this->redirect()->toRoute('home');
				return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'1'));
                    
            }
        }

        return new ViewModel(array('form' => $form));
        
/*
 *      $pubKey = "6Lf28wgAAAAAAGAgFXzfkfya41viWL7ASgJLbmZ6";
        $privKey = "6Lf28wgAAAAAALUJ9fwU1bgAZ6K9oFg253u0gzYs";
        $captcha = new ReCaptcha($pubKey, $privKey);
  */      

    }
    
    public function step2Action(){
    	/*
		'driverOptions' => array(
                        1002=>'SET NAMES \'UTF8\''
                	)
		*/
        $records = $form = array();
        $token = $this->params('token', false);
        
        $repository = $this->getEm()->getRepository("Application\Entity\Users");
        $obj_records_users = $repository->findByToken($token);
        
        if(!empty($obj_records_users)){
            //$records_users = $obj_records_users->toArray();
            //$records['email'] = $obj_records_users->email;
            //$records['token'] = $token;
            
            $records = $obj_records_users->getArrayCopy();
				
			if(empty($records)):
				return $this->redirect()->toRoute('home-message',array('tipo'=>'ferror','ref'=>'register','cod_msg'=>'1'));
				exit;
			elseif(!empty($records['nome']) && !empty($records['senha'])):
				return $this->redirect()->toRoute('home-message',array('tipo'=>'ferror','ref'=>'register','cod_msg'=>'2'));
				exit;
			endif;
			
            $form = $this->getServiceLocator()->get("service_register_step2_form");
            
            $form->setData($records);
            
            $request = $this->getRequest();
        
            if ($request->isPost()) {
                $form->setData($request->getPost());
				
                if ($form->isValid()) {
                	
                    $service = $this->getServiceLocator()->get("service_register");
                    $records = $request->getPost()->toArray();
                    //$records['token'] = '';
                    $data = new \DateTime("now America/Sao_Paulo");
                    $records['data_alteracao']  = $data;
                    $records['diretorio']       = 'files/'.$data->format("Y").'/'.$data->format("m").'/'.$data->format("d").'/'.$records['id'].'/';
                    $records['senha']           = $service->encryptPassword($records['senha']);
                    $service->update($records);
                    //$service->SendEmail($records);    
                    return $this->redirect()->toRoute('home-message',array('tipo'=>'fsuccess','ref'=>'register','cod_msg'=>'2'));
                }
                
                //$form->setData($obj_records_users); 
            }
        }
        
        return new ViewModel(array('form'=>$form,'records'=>$records));
    }
    
    public function viewtplAction(){
        $records['token'] = md5(uniqid(time()));
        $new_model = new ViewModel(array('dados'=>$records));
        $new_model->setTemplate('login/register/confirmation-email');
        return $new_model;
        //$tpl = $this->emailRenderer->render($new_model);
        //echo "test";
    }
    
    public function generateAction()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $id = $this->params('id', false);

        if ($id) {

            $image = './data/captcha/' . $id;

            if (file_exists($image) !== false) {
                $imagegetcontent = @file_get_contents($image);

                $response->setStatusCode(200);
                $response->setContent($imagegetcontent);

                if (file_exists($image) == true) {
                    unlink($image);
                }
            }

        }

        return $response;
    }
    
        /*
     * @return EntityManager
     */

    protected function getEm() {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;
    }

}


/*
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;

class AlbumController extends AbstractActionController
{
    protected $albumTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album', array('action'=>'add'));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id' => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }    
}
*/