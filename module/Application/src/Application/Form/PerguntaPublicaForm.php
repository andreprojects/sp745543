<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\PerguntaPublicaFilter;


class PerguntaPublicaForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('perguntapublica');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new PerguntaPublicaFilter);
        
        
        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'class' =>'span3',
                'placeholder' => 'Digite seu nome'
            ),
            'options' => array(
                'label' => 'Nome',
            ),
        ));
        

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'span3',
                'placeholder' => 'Digite seu email'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'msg_pergunta',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Textarea',
                'rows'  => 5,
                'class' => 'span5',
                'placeholder' => 'Digite sua Pergunta'
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
