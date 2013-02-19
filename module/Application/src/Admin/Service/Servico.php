<?php

namespace Admin\Service;

//use Zend\View\Renderer\PhpRenderer;
use Doctrine\ORM\EntityManager;

class Servico extends AbstractService {
  
    /**
     * @var EntityManager
     */
    protected $em;
    protected $entity;
    protected $emailRenderer;
    
    public function __construct(EntityManager $em) {
        parent::__construct($em);
        $this->entity = "Application\Entity\Servico";
        
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