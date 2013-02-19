<?php
namespace Application\Form;

use Zend\Form\Form;
//use Application\Form\FaleConoscoFilter;

class AreaStep3Form extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('step3');

        $this->setAttribute('method', 'post');
        
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
                'class' =>'campotxt campotxtstep',
                'maxlength' => '100'
            ),
            'options' => array(
                'label' => 'Titulo',
            ),
        ));
        

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type'  => 'text',
                'class' =>'campotxt campotxtstep',
                'maxlength' => '100'
            ),
            'options' => array(
                'label' => 'URL',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Enviar',
                'id' => 'submitbutton',
            ),
        ));

    }
}
