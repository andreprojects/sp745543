<?php
namespace Admin\Form;

use Zend\InputFilter\InputFilter;


class PlanoFilter extends InputFilter{
    
     
    public function __construct()
    {

        $this->add(array(
            'name'     => 'nome',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
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
            'name'     => 'preco',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
            'validators' => array(
                array(
                    'name'    => 'Zend\I18n\Validator\Float',
                ),
            ),
            
        ));

        $this->add(array(
            'name'     => 'dia_publicacao',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));
    }
}
