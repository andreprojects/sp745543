<?php
namespace Application\Form;

use Zend\InputFilter\InputFilter;


class AnuncioFilter extends InputFilter{
    
     
    public function __construct()
    {

        $this->add(array(
            'name'     => 'titulo',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                        )
                    ),
            ),
            
        ));

        $this->add(array(
            'name'     => 'descricao',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

        $this->add(array(
            'name'     => 'id_usuario',
            'required' => true,
            'filters'  => array(
                array('name'    => 'Int'),
                
            ),
            
            
        ));


    }
}
