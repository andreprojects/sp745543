<?php
namespace Application\Form;

use Zend\InputFilter\InputFilter;


class RespostaPerguntaFilter extends InputFilter{
    
     
    public function __construct()
    {
      
      $this->add(array(
                'name'     => 'msg_resposta',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                )
            ));
        
    }
}
