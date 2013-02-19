<?php
namespace Login\Form;

use Zend\InputFilter\InputFilter;


class ChangePasswordFilter extends InputFilter{
    
     
    public function __construct()
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => true,
            'filters' => array(
                array('name'    => 'Int'),
            ),
        ));
        
        $this->add(array(
             'name'     => 'senha',
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
                         'min'      => 6,
                         'max'      => 8,
                     )
                 ),
             ),
         ));
         
         $this->add(array(
             'name'     => 'contra_senha',
             'required' => true,
             'filters'  => array(
                 array('name' => 'StripTags'),
                 array('name' => 'StringTrim')
                 
             ),
             'validators' => array(
                 array(
                     'name'    => 'Callback',
                     'options' => array(
                          'callback' => array($this, 'CheckPassword'),
                          'message' => 'Senha não confere'
                     )
                 ),
             ),
         ));
        
    }
    
    
    public function CheckPassword($contra_senha){
        
        if($this->getRawValue('senha') != $contra_senha)
            return false;
        else
            return true;
    }
}
