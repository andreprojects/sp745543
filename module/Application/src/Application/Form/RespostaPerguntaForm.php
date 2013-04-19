<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\RespostaPerguntaFilter;


class RespostaPerguntaForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('respostapergunta');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new RespostaPerguntaFilter);
        
        $this->add(array(
            'name' => 'msg_resposta',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Textarea',
                'rows'  => 5,
                'class' => 'span5',
                'placeholder' => 'Digite sua Resposta',
                'maxlength' => '180'
            ),
            'options' => array(
                'label' => 'Mensagem',
            ),
        ));

    }
}
