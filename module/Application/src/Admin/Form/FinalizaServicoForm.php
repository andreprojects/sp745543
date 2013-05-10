<?php
namespace Admin\Form;


use Zend\Form\Form;
use Admin\Form\FinalizaServicoFilter;


class FinalizaServicoForm extends Form
{
    public function __construct($baseUrl = null)
    {
        // we want to ignore the name passed
        parent::__construct('finalizaservico');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new FinalizaServicoFilter);
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden'
            )

        ));

        $this->add(array(
            'name' => 'data_inicio',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Data inicio",
            )

        ));


        $this->add(array(
            'name' => 'data_fim',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Data fim",
            )

        ));

        $this->add(array(
            'name' => 'media_clique',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Média clique",
            )

        ));

        $this->add(array(
            'name' => 'posicao_media',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Posição media",
            )

        ));

        $this->add(array(
            'name' => 'impressao',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Impressões",
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
