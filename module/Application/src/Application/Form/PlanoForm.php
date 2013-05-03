<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\PlanoFilter;


class PlanoForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('plano');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new PlanoFilter);
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden'
            )
        ));
/*
        $this->add(array(

            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_servico',
            'options' => array(
                'label' => 'Gender',
                'value_options' => array(
                ),

            ),
            'attributes' => array(
             // 'value' => '1' //set selected to '1'
            )

        ));*/


        $this->add(array(
            'name' => 'site',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Digite a URL do seu site",
                'onkeyup'=>'$("#urlsel").html(this.value);',
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
