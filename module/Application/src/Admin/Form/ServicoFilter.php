<?php
namespace Admin\Form;

use Zend\InputFilter\InputFilter;


class ServicoFilter extends InputFilter{
    
     
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
    }
}
