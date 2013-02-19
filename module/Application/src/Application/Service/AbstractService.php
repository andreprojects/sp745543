<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface as ViewRenderer;

abstract class AbstractService {

    /**
     * @var EntityManager
     */
    protected $em;
    protected $entity;
    protected $emailRenderer;
    
    protected $mail_username='outmarcas@gmail.com';
    protected $mail_password='xoutandre';
    
    protected $mail_form_email = 'anunciomosaico@gmail.com';
    protected $mail_form_name = 'Anúncio Mosaico';
    
    protected $mail_to_email = 'andrework@gmail.com';
    protected $mail_to_name = '';
    
    protected $mail_subject = '1° Etapa do cadastro realizada com sucesso';
    protected $mail_template = 'login/register/confirmation-email';
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function insert(array $data) {
        $entity = new $this->entity($data);
        $entity->populate($data);
        $this->em->persist($entity);
        $this->em->flush();
        return $entity->id;
    }
    
    public function update(array $data) {
        $entity = $this->em->getReference($this->entity, $data['id']);
        //$entity = Configurator::configure($entity, $data);
        foreach($data as $k => $v){
            $entity->$k = $v;
        }
        
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }
    
    public function delete($id) {
        $entity = $this->em->getReference($this->entity, $id);
        if($entity) {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }
    }
    
    public function setMessageRenderer(ViewRenderer $emailRenderer)
    {
        $this->emailRenderer = $emailRenderer;
        return $this;
    }
    
    
    public function SendEmail(Array $records){

        $options = new SmtpOptions( array(
        "name" => "gmail",
        "host" => "smtp.gmail.com",
        "port" => 587,
        "connection_class" => "plain",
        "connection_config" => array(   "username" => $this->mail_username,
                                        "password" => $this->mail_password,
                                        "ssl"      => "tls" )
        ) );
        
        $mail = new Mail\Message();
        $mail->setFrom($this->mail_form_email, $this->mail_form_name);
        
        $emailTo = !empty($records['email']) ? $records['email'] : $this->mail_to_email;
        $emailNameTo = !empty($records['nome']) ? $records['nome'] : $this->mail_to_name;
        
        $mail->addTo($emailTo, $emailNameTo);
        //$mail->addCC( 'ao@gmail.com' );
        
        $mail->setSubject($this->mail_subject);
        
        //$tpl = $this->getTemplateRenderer('login/register/confirmation-email',$dadosBody);
        $new_model = new ViewModel(array('dados'=>$records));
        $new_model->setTemplate($this->mail_template);
        $tpl = $this->emailRenderer->render($new_model);
        
        //$text = new MimePart($tpl);
        //$text->type = "text/plain";
        
        $html = new MimePart($tpl);
        $html->type = "text/html";
        
        //$image = new MimePart(fopen($pathToImage));
        //$image->type = "image/jpeg";
        
        $body = new MimeMessage();
        $body->setParts(array($html));
        
        $mail->setBody($body);
        
        $transport = new SmtpTransport();
        $transport->setOptions( $options );
        $transport->send($mail);
        
    }
    
    public function encryptPassword($password) {
        //colocar arquivo config
        $salt = "xhj28$83(*kdi#jjs";
        $hashSenha = "";
        if(!empty($password))
        {
          $hashSenha = hash('sha512', $password . $salt);
          for ($i = 0; $i < 64000; $i++)
            $hashSenha = hash('sha512', $hashSenha);
        }
        return $hashSenha;
    }
    
    
    
    
}
