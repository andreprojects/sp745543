<?php
namespace Admin\Form;


use Zend\Form\Form;
use Admin\Form\LoginFilter;


class PlanoForm extends Form
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
                'placeholder'=>"Nome do Plano",
            )

        ));

        $this->add(array(
            'name' => 'descricao',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\Textarea',
                'rows'  => "3"
            )

        ));

         $this->add(array(
            'name' => 'preco',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Preço",
            )

        ));

          $this->add(array(
            'name' => 'dia_publicacao',
            'attributes' => array(
                'type'  => 'text',
                'placeholder'=>"Dia da Publicação",
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
