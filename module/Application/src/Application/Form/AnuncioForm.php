<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\AnuncioFilter;


class AnuncioForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('anuncio');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new AnuncioFilter);
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden'
            )

        ));


        $this->add(array(
            'name' => 'id_usuario',
            'attributes' => array(
                'type'  => 'hidden'
            )

        ));



        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
                'class'  => "span4",
                'placeholder'=>"O que você oference?",
                'maxlength' => "50"
            )

        ));

        $this->add(array(
            'name' => 'descricao',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Textarea',
                'class'  => "span4",
                'rows'  => "3"
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
