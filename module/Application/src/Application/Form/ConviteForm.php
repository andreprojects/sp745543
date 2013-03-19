<?php
namespace Application\Form;


use Zend\Form\Form;
use Application\Form\AnuncioFilter;


class ConviteForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('convite');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new ConviteFilter);
		
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
                'class'  => "",
                'placeholder'=>"Nome do destinatário",
            )

        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class'  => "span4",
                'placeholder'=>"Email do destinatário",
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
