<?php

namespace Application\Service;

//use Zend\View\Renderer\PhpRenderer;
use Doctrine\ORM\EntityManager;


class Pergunta extends AbstractService {
  
    /**
     * @var EntityManager
     */
    protected $em;
    protected $entity;
    protected $emailRenderer;
    
    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->entity = "Application\Entity\Pergunta";
        $this->mail_template = "application/pergunta/pergunta-publica-email";
        //$this->mail_template = "application/pergunta/resposta-email";
        //$this->mail_subject = "Formulário Fale Conosco";
        /*$this->mail_form_name = "OutMarcas";*/
        
    }

    public function setMailSubject($subject){
        $this->mail_subject = $subject;
    }

    public function setMailTemplate($template){
        $this->mail_template = $template;
    }
    
    

    /*
    public function getTemplateRenderer($nameTemplate,Array $dados){

        //$resolver = $this->getResolver();
        $renderer = new PhpRenderer();
        //$renderer->setResolver($resolver);
        
        $new_model = new ViewModel(array('dados'=>$dados));
        $new_model->setTerminal(true)->setTemplate($nameTemplate);

        return $renderer->render($new_model);
    }*/
  
}