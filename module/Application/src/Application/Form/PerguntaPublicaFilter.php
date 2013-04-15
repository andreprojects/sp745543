<?php
namespace Application\Form;

use Zend\InputFilter\InputFilter;


class PerguntaPublicaFilter extends InputFilter{
    
     
    public function __construct()
    {
      
      
      $this->add(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                )
            ));
      
      
            
      $this->add(array(
          'name'     => 'email',
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
                      'max'      => 30,
                  ),
                  'name'    => 'EmailAddress',
                  'options' => array(
                      'useMxCheck' => true
                  ),
                 
              ),
          ),
      ));
      
      
      $this->add(array(
                'name'     => 'msg_pergunta',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                    
                )
            ));
        
    }
}
