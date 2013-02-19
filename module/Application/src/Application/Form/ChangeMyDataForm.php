<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\ChangeMyDataFilter;


class ChangeMyDataForm extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('changemydata');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new ChangeMyDataFilter);
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'campotxt'
            ),
            'options' => array(
                'label' => 'Nome Completo*',
            ),
        ));
        
        $this->add(array(
            'name' => 'cep',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'campotxt'
            ),
            'options' => array(
                'label' => 'Cep: (Opcional)',
            ),
        ));
        
         $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'campotxt'
            ),
            'options' => array(
                'label' => 'Senha:',
            ),
        ));
         
         $this->add(array(
            'name' => 'contra_senha',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'campotxt'
            ),
            'options' => array(
                'label' => 'Confirme sua senha:',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

    }
}
