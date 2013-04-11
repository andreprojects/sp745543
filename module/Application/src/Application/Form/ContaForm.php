<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\ContaFilter;


class ContaForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('conta');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new ContaFilter);
        
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
        

        

    }
}
