<?php
namespace Admin\Form;

use Zend\InputFilter\InputFilter;


class FinalizaServicoFilter extends InputFilter{
    
     
    public function __construct()
    {

        $this->add(array(
            'name'     => 'data_inicio',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

        $this->add(array(
            'name'     => 'data_fim',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

        $this->add(array(
            'name'     => 'media_clique',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

        $this->add(array(
            'name'     => 'posicao_media',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

        $this->add(array(
            'name'     => 'impressao',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));
    }
}
