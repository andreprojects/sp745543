<?php
namespace Admin\Form;


use Zend\Form\Form;
use Admin\Form\LoginFilter;


class ServicoForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('servico');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new ServicoFilter);
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden'
            )

        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Nome do Serviço",
            )

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
