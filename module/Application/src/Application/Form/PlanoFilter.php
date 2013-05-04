<?php
namespace Application\Form;

use Zend\InputFilter\InputFilter;


class PlanoAnuncioFilter extends InputFilter{
    
     
    public function __construct()
    {

        $this->add(array(
            'name'     => 'site',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
                
            ),
            
        ));

       
    }
}
