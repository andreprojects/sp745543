<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Aws\S3\S3Client;
use Aws\Common\Enum\Region;
//use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;
use Guzzle\Http\EntityBody;

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

    public function testeAction(){

        $aws    = $this->getServiceLocator()->get('aws');
        $s3 = $aws->get('s3');
		
        $uri = $this->getRequest()->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        $base = sprintf('%s://%s', $scheme, $host);
        $url_file = getcwd()."/public/img/logo/logosp160.png";
        $body = fopen($url_file, 'r');
        $bucket= 'shareplaque-images';

        try {
            $response= $s3->listObjects(array('Bucket' => $bucket,'Prefix' => 'teste/'));
            var_dump($response->toArray());

            $s3->putObject(array(
                'Bucket' => 'shareplaque-images',
                'Key'    => 'teste/logosp160xxx.png',
                'Body'   => EntityBody::factory($body),
                'ACL'    => CannedAcl::PUBLIC_READ,
                'ContentType' => 'image/png',
                'ContentLength' => filesize($url_file),
            ));
            echo "Enviado com sucesso"; 
			

        } catch (S3Exception $e) {
            echo "There was an error uploading the file.\n ".$e->getMessage();
        }

        return $this->response;
    }
    
    public function indexAction() {

        
        $form = $this->getServiceLocator()->get("service_faleconosco_form");
        $request = $this->getRequest();
        $msg = array(0);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                
                $service = $this->getServiceLocator()->get("service_faleconosco");
                $records = $request->getPost()->toArray();
                //$service->insert($records);
                $service->SendEmail($records);
                $msg['ref']     = "faleconosco";
                $msg['tipo']    = "success";    
                $msg['cod_msg'] = "1";

                //return $this->redirect()->toRoute('home-message',array('msg_id'=>'13'));
            }
        }

        return new ViewModel(array('form' => $form,'msg' => $msg));
    }


}
