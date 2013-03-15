<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\FaleConoscoFilter;


class FaleConoscoForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('faleconosco');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new FaleConoscoFilter);
        
        
        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'class' =>'span4',
                'placeholder' => 'Digite seu nome'
            ),
            'options' => array(
                'label' => 'Nome',
            ),
        ));
        

        $this->add(array(
            'name' => 'emailfc',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'span4',
                'placeholder' => 'Digite seu email'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'msg',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Textarea',
                'rows'  => 5,
                'class' => 'span4',
                'placeholder' => 'Digite sua Mensagem'
            ),
            'options' => array(
                'label' => 'Mensagem',
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
